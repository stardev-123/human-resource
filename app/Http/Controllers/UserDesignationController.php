<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserDesignationRequest;
use Entrust;
use App\UserDesignation;

Class UserDesignationController extends Controller{
    use BasicController;

    protected $form = 'user-designation-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        if($user->hasRole(DEFAULT_ROLE))
            return ['message' => trans('messages.permission_denied'),'status' => 'error'];

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

        return view('user_designation.list',compact('user'))->render();
    }

    public function show(UserDesignation $user_designation){
        $user = $user_designation->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_designation.show',compact('user','user_designation','values','col_ids','custom_fields'));
    }

    public function edit(UserDesignation $user_designation){
        $user = $user_designation->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $designations = \App\Designation::whereIsHidden(0)->whereIn('id',getDesignation())->get()->pluck('designation_with_department','id')->all();
        $custom_user_designation_field_values = getCustomFieldValues($this->form,$user_designation->id);
        
        return view('user_designation.edit',compact('user_designation','custom_user_designation_field_values','designations'));
    }

    public function store(UserDesignationRequest $request, $user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserDesignation::whereUserId($user_id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserDesignation::whereUserId($user_id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $designation = UserDesignation::whereUserId($user_id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $designation = UserDesignation::whereUserId($user_id)->where('from_date','<=',$request->input('from_date'))
                        ->where('to_date','>=',$request->input('from_date'))->count();

        if($designation)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_designation = new UserDesignation;
        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_designation->fill($data)->save();
        $user->userDesignation()->save($user_designation);

        $profile = $user->Profile;
        $current_designation_id = getUserDesignation(date('Y-m-d'),$user_id);
        $profile->designation_id = ($current_designation_id) ? : null;
        $profile->save();
        storeCustomField($this->form,$user_designation->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'designation','sub_module_id' => $user_designation->id]);

        return response()->json(['message' => trans('messages.designation').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserDesignationRequest $request, UserDesignation $user_designation){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_designation->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserDesignation::whereUserId($user_designation->user_id)->where('id','!=',$user_designation->id)->whereNull('to_date')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserDesignation::whereUserId($user_designation->user_id)->where('id','!=',$user_designation->id)->first();

        if($previous_record && $request->input('from_date') <= $previous_record->from_date)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('to_date'))
            $designation = UserDesignation::whereUserId($user_designation->user_id)->where('id','!=',$user_designation->id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('from_date','<=',$request->input('from_date'))
                    ->where('to_date','>=',$request->input('from_date'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('from_date','<=',$request->input('to_date'))
                            ->where('to_date','>=',$request->input('to_date'));
                    });})->count();
        else
            $designation = UserDesignation::whereUserId($user_designation->user_id)->where('id','!=',$user_designation->id)->where('from_date','<=',$request->input('from_date'))
                        ->where('to_date','>=',$request->input('from_date'))->count();

        if($designation)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $data = $request->all();
        $data['to_date'] = ($request->has('to_date')) ? $request->input('to_date') : null;
        $user_designation->fill($data)->save();

        $profile = $user->Profile;
        $current_designation_id = getUserDesignation(date('Y-m-d'),$user->id);
        $profile->designation_id = ($current_designation_id) ? : null;
        $profile->save();
        updateCustomField($this->form,$user_designation->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'designation','sub_module_id' => $user_designation->id]);

        return response()->json(['message' => trans('messages.designation').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserDesignation $user_designation){
        $user = $user_designation->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user->UserDesignation->count() == 1)
            return response()->json(['message' => trans('messages.primary_designation_cannot_be_deleted'), 'status' => 'error']);

        if($user_designation->designation_id == $user->Profile->designation_id){
            $profile = $user->Profile;
            $profile->designation_id = null;
            $profile->save();
        }

        deleteCustomField($this->form, $user_designation->id);
        
        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'designation','sub_module_id' => $user_designation->id]);

        $user_designation->delete();

        return response()->json(['message' => trans('messages.designation').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}