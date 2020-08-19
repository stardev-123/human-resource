<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserEmploymentRequest;
use Entrust;
use App\UserEmployment;

Class UserEmploymentController extends Controller{
    use BasicController;

    protected $form = 'user-employment-form';

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

        return view('user_employment.list',compact('user'))->render();
    }

    public function show(UserEmployment $user_employment){
        $user = $user_employment->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_employment.show',compact('user','user_employment','values','col_ids','custom_fields'));
    }

    public function edit(UserEmployment $user_employment){
        $user = $user_employment->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(!Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $custom_user_employment_field_values = getCustomFieldValues($this->form,$user_employment->id);
        
        return view('user_employment.edit',compact('user_employment','custom_user_employment_field_values'));
    }

    public function store(UserEmploymentRequest $request, $user_id){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserEmployment::whereUserId($user_id)->whereNull('date_of_leaving')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserEmployment::whereUserId($user_id)->first();

        if($previous_record && $request->input('date_of_joining') <= $previous_record->date_of_joining)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('date_of_leaving'))
            $employment = UserEmployment::whereUserId($user_id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('date_of_joining','<=',$request->input('date_of_joining'))
                    ->where('date_of_leaving','>=',$request->input('date_of_joining'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('date_of_joining','<=',$request->input('date_of_leaving'))
                            ->where('date_of_leaving','>=',$request->input('date_of_leaving'));
                    });})->count();
        else
            $employment = UserEmployment::whereUserId($user_id)->where('date_of_joining','<=',$request->input('date_of_joining'))->where('date_of_leaving','>=',$request->input('date_of_joining'))->count();

        if($employment)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $user_employment = new UserEmployment;
        $data = $request->all();
        $data['date_of_leaving'] = ($request->has('date_of_leaving')) ? $request->input('date_of_leaving') : null;
        $user_employment->fill($data)->save();
        $user->userEmployment()->save($user_employment);
        storeCustomField($this->form,$user_employment->id, $data);

        $user_current_employment = getEmployment(date('Y-m-d'),$user->id);
        if(!$user->hasRole(DEFAULT_ROLE) && !$user_current_employment){
            $user->status = 'inactive' ;
            $user->save();
        }

        $this->logActivity(['module' => 'user','module_id' => $user_id, 'activity' => 'added','sub_module' => 'employment','sub_module_id' => $user_employment->id]);

        return response()->json(['message' => trans('messages.employment').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(UserEmploymentRequest $request, UserEmployment $user_employment){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_employment->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserEmployment::whereUserId($user_employment->user_id)->where('id','!=',$user_employment->id)->whereNull('date_of_leaving')->count())
            return response()->json(['message' => trans('messages.already_undefined_end_date'), 'status' => 'error']);

        $previous_record = UserEmployment::whereUserId($user_employment->user_id)->where('id','!=',$user_employment->id)->first();

        if($previous_record && $request->input('date_of_joining') <= $previous_record->date_of_joining)
            return response()->json(['message' => trans('messages.back_date_entry'), 'status' => 'error']);

        if($request->has('date_of_leaving'))
            $employment = UserEmployment::whereUserId($user_employment->user_id)->where('id','!=',$user_employment->id)
                ->where(function ($query) use($request) { $query->where(function ($query) use($request){
                    $query->where('date_of_joining','<=',$request->input('date_of_joining'))
                    ->where('date_of_leaving','>=',$request->input('date_of_joining'));
                    })->orWhere(function ($query) use($request) {
                        $query->where('date_of_joining','<=',$request->input('date_of_leaving'))
                            ->where('date_of_leaving','>=',$request->input('date_of_leaving'));
                    });})->count();
        else
            $employment = UserEmployment::whereUserId($user_employment->user_id)->where('id','!=',$user_employment->id)->where('date_of_joining','<=',$request->input('date_of_joining'))
                        ->where('date_of_leaving','>=',$request->input('date_of_joining'))->count();

        if($employment)
            return response()->json(['message' => trans('messages.entry_already_defined'), 'status' => 'error']);

        $data = $request->all();
        $data['date_of_leaving'] = ($request->has('date_of_leaving')) ? $request->input('date_of_leaving') : null;
        $user_employment->fill($data)->save();
        updateCustomField($this->form,$user_employment->id, $data);

        $user_current_employment = getEmployment(date('Y-m-d'),$user->id);
        if(!$user->hasRole(DEFAULT_ROLE) && !$user_current_employment){
            $user->status = 'inactive' ;
            $user->save();
        }

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'updated','sub_module' => 'employment','sub_module_id' => $user_employment->id]);

        return response()->json(['message' => trans('messages.employment').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, UserEmployment $user_employment){
        $user = $user_employment->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if(!Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user->UserEmployment->count() == 1)
            return response()->json(['message' => trans('messages.primary_employment_cannot_be_deleted'), 'status' => 'error']);

        deleteCustomField($this->form, $user_employment->id);
        
        $this->logActivity(['module' => 'user','module_id' => $user->id, 'activity' => 'deleted','sub_module' => 'employment','sub_module_id' => $user_employment->id]);

        $user_employment->delete();

        return response()->json(['message' => trans('messages.employment').' '.trans('messages.deleted'), 'status' => 'success']);
    }

}