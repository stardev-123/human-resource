<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserShiftRequest;
use Entrust;
use App\UserShift;

Class UserShiftController extends Controller{
    use BasicController;

    protected $form = 'user-shift-form';

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

        return view('user_shift.list',compact('user'))->render();
    }

    public function create($user_id){
        $user = \App\User::find($user_id);

        if(!$user)
            return view('global.error',['message' => trans('messages.invalid_link')]);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $shifts = \App\Shift::all()->pluck('name','id')->all();
        
        return view('user_shift.create',compact('shifts','user'));
    }

    public function show(UserShift $user_shift){
        $user = $user_shift->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_shift.show',compact('user','user_shift','values','col_ids','custom_fields'));
    }

    public function edit(UserShift $user_shift){
        $user = $user_shift->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $shifts = \App\Shift::all()->pluck('name','id')->all();
        $custom_user_shift_field_values = getCustomFieldValues($this->form,$user_shift->id);
        $in_time = isset($user_shift->in_time) ? date('h:iA',strtotime($user_shift->in_time)) : '';
        $out_time = isset($user_shift->out_time) ? date('h:iA',strtotime($user_shift->out_time)) : '';
        
        return view('user_shift.edit',compact('user_shift','custom_user_shift_field_values','shifts','in_time','out_time'));
    }

    public function store(UserShiftRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserShift::whereUserId($user_id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserShift::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $shift = UserShift::whereUserId($user_id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $shift = UserShift::whereUserId($user_id)->where('from_date','<=',$request->input('from_date'))->where('to_date','>=',$request->input('from_date'))->count();

        if($shift)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_shift = new UserShift;
        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_shift->fill($data);

        $user_shift->shift_id = ($request->input('shift_type') == 'predefined') ? $request->input('shift_id') : null;
        $user_shift->in_time = ($request->input('shift_type') == 'custom') ? date('H:i:s',strtotime($request->input('in_time'))) : null;
        $user_shift->out_time = ($request->input('shift_type') == 'custom') ? date('H:i:s',strtotime($request->input('out_time'))) : null;
        $user_shift->overnight = ($request->has('overnight')) ? 1 : 0;

        $user_shift->save();
        $user->userShift()->save($user_shift);
        storeCustomField($this->form,$user_shift->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'shift','sub_module_id' => $user_shift->id]);

        return response()->json(['message' => trans('messages.shift').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserShiftRequest $request, UserShift $user_shift){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_shift->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserShift::whereUserId($user_shift->user_id)->where('id','!=',$user_shift->id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserShift::whereUserId($user_shift->user_id)->where('id','!=',$user_shift->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $shift = UserShift::whereUserId($user_shift->user_id)->where('id','!=',$user_shift->id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $shift = UserShift::whereUserId($user_shift->user_id)->where('id','!=',$user_shift->id)->where('from_date','<=',$request->input('from_date'))
                        ->where('to_date','>=',$request->input('from_date'))->count();

        if($shift)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_shift->fill($data);

        $user_shift->shift_id = ($request->input('shift_type') == 'predefined') ? $request->input('shift_id') : null;
        $user_shift->in_time = ($request->input('shift_type') == 'custom') ? date('H:i:s',strtotime($request->input('in_time'))) : null;
        $user_shift->out_time = ($request->input('shift_type') == 'custom') ? date('H:i:s',strtotime($request->input('out_time'))) : null;
        $user_shift->overnight = ($request->has('overnight')) ? 1 : 0;

        $user_shift->save();
        updateCustomField($this->form,$user_shift->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'shift','sub_module_id' => $user_shift->id]);

        return response()->json(['message' => trans('messages.shift').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserShift $user_shift){
        $user = $user_shift->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
    
        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteCustomField($this->form, $user_shift->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'shift','sub_module_id' => $user_shift->id]);

        $user_shift->delete();

        return response()->json(['message' => trans('messages.shift').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}