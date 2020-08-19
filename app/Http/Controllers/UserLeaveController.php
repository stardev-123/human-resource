<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserLeaveRequest;
use Entrust;
use App\UserLeave;

Class UserLeaveController extends Controller{
    use BasicController;

    protected $form = 'user-leave-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        if(!$this->userAccessible($user))
            return ['message' => trans('messages.permission_denied'),'status' => 'error'];
        else
            return ['status' => 'success'];
    }

    public function lists(Request $request){
        $user = \App\User::find($request->input('id'));

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return;

        $leave_types = \App\LeaveType::all();

        return view('user_leave.list',compact('user','leave_types'))->render();
    }

    public function edit(UserLeave $user_leave){
        $user = $user_leave->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $leave_types = \App\LeaveType::all();
        $user_leave_details = $user_leave->UserLeaveDetail->pluck('leave_assigned','leave_type_id')->all();
        $custom_user_leave_field_values = getCustomFieldValues($this->form,$user_leave->id);
        
        return view('user_leave.edit',compact('user_leave','custom_user_leave_field_values','leave_types','user_leave_details'));
    }

    public function show(UserLeave $user_leave){
        $user = $user_leave->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_leave.show',compact('user_leave','custom_fields','col_ids','values'));
    }

    public function store(UserLeaveRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $previous_record = UserLeave::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        $leave = UserLeave::whereUserId($user_id)
            ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                $query->where('from_date','<=',$request->input('from_date'))
                ->where('to_date','>=',$request->input('from_date'));
                })->orWhere(function ($query) use($request) {
                    $query->where('from_date','<=',$request->input('to_date'))
                        ->where('to_date','>=',$request->input('to_date'));
                });})->count();

        if($leave)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_leave = new UserLeave;
        $data = $request->all();
        $user_leave->fill($data);
        $user_leave->save();
        $user->userLeave()->save($user_leave);

        $leave_types = $request->input('leave_type');
        foreach($leave_types as $key => $value){
            $user_leave_detail = new \App\UserLeaveDetail;
            $user_leave_detail->leave_type_id = $key;
            $user_leave_detail->leave_assigned = $value;
            $user_leave_detail->user_leave_id = $user_leave->id;
            $user_leave_detail->save();
        }
        storeCustomField($this->form,$user_leave->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'leave','sub_module_id' => $user_leave->id]);

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserLeaveRequest $request, UserLeave $user_leave){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_leave->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $previous_record = UserLeave::whereUserId($user_leave->user_id)->where('id','!=',$user_leave->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error','previous_record' => $previous_record]);

        $existing_user_leave = UserLeave::whereUserId($user_leave->user_id)->where('id','!=',$user_leave->id)
            ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                $query->where('from_date','<=',$request->input('from_date'))
                ->where('to_date','>=',$request->input('from_date'));
                })->orWhere(function ($query) use($request) {
                    $query->where('from_date','<=',$request->input('to_date'))
                        ->where('to_date','>=',$request->input('to_date'));
                });})->count();

        if($existing_user_leave)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $leave_used_error = 0;
        $leave_types = $request->input('leave_type');
        foreach($leave_types as $key => $value){
            $existing_leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$key)->first();
            if($existing_leave_detail && $existing_leave_detail->leave_used > $value)
                $leave_used_error++;
        }

        if($leave_used_error)
            return response()->json(['message' => trans('messages.leave_used_error'), 'status' => 'error']);

        $leaves = \App\Leave::whereUserId($user->id)->where('from_date','>=',$user_leave->from_date)->where('to_date','<=',$user_leave->to_date)->get();

        $leave_used_error = 0;
        foreach($leaves as $leave)
            if($leave->from_date < $request->input('from_date') || $leave->to_date > $request->input('to_date'))
                $leave_used_error++;

        if($leave_used_error)
            return response()->json(['message' => trans('messages.leave_used_error'), 'status' => 'error']);

        $data = $request->all();
        $user_leave->fill($data)->save();

        foreach($leave_types as $key => $value){
            $user_leave_detail = \App\UserLeaveDetail::firstOrNew(['user_leave_id' => $user_leave->id, 'leave_type_id' => $key]);
            $user_leave_detail->leave_assigned = $value;
            $user_leave_detail->save();
        }
        updateCustomField($this->form,$user_leave->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'leave','sub_module_id' => $user_leave->id]);

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserLeave $user_leave){
        $user = $user_leave->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $leave_used = 0;
        foreach($user_leave->UserLeaveDetail as $user_leave_detail)
            if($user_leave_detail->leave_used)
                $leave_used++;

        if($leave_used)
            return response()->json(['message' => trans('messages.leave_used_cannot_be_deleted',['attribute' => $leave_used]), 'status' => 'error']);

        deleteCustomField($this->form, $user_leave->id);
        
        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'leave','sub_module_id' => $user_leave->id]);

        $user_leave->delete();

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}