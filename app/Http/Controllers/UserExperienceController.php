<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserExperienceRequest;
use Entrust;
use App\UserExperience;

Class UserExperienceController extends Controller{
    use BasicController;

    protected $form = 'user-experience-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        $self_service = (config('config.user_manage_own_experience')) ? 1 : 0;

        if(!$this->userAccessible($user,$self_service))
            return ['message' => trans('messages.permission_denied'),'status' => 'error'];
        else
            return ['status' => 'success'];
    }

    public function lists(Request $request){

        $user = \App\User::find($request->input('id'));

        $accessible = $this->accessible($user);

        if($accessible['status'] != 'success')
            return;

        return view('user_experience.list',compact('user'))->render();
    }

    public function toggleLock(Request $request){
        $user_experience = UserExperience::find($request->input('id'));

        if(!$user_experience)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if(($user_experience->user_id == \Auth::user()->id && count(getParent())) || ($user_experience->user_id != \Auth::user()->id && !Entrust::can('edit-user')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $user_experience->is_locked = ($user_experience->is_locked) ? 0 : 1;
        $user_experience->save();

        return response()->json(['message' => '', 'status' => 'success']);
    }

    public function show(UserExperience $user_experience){
        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();
        $uploads = \App\Upload::whereModule('user-experience')->whereModuleId($user_experience->id)->whereStatus(1)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_experience.show',compact('user','user_experience','values','col_ids','custom_fields','uploads'));
    }

	public function store(UserExperienceRequest $request,$user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserExperience::whereUserId($user->id)->whereCompanyName($request->input('company_name'))->whereFromDate($request->input('from_date'))->whereToDate($request->input('to_date'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.experience')]),'status' => 'error']);

        $upload_validation = validateUpload('user-experience',$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $user_experience = new UserExperience;
        $data = $request->all();
	    $user_experience->fill($data);
        $user_experience->save();
        $user->userExperience()->save($user_experience);
        storeUpload('user-experience',$user_experience->id,$request);
        storeCustomField($this->form,$user_experience->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'added','sub_module' => 'experience', 'sub_module_id' => $user_experience->id]);

        return response()->json(['message' => trans('messages.experience').' '.trans('messages.added'), 'status' => 'success']);
	}

    public function edit(UserExperience $user_experience){
        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if($user_experience->is_locked && $user_experience->user_id == \Auth::user()->id && count(getParent()))
            return view('global.error',['message' => trans('messages.permission_denied')]);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $custom_user_experience_field_values = getCustomFieldValues($this->form,$user_experience->id);

        $uploads = editUpload('user-experience',$user_experience->id);
        return view('user_experience.edit',compact('user_experience','custom_user_experience_field_values','uploads'));
    }

    public function update(UserExperienceRequest $request, UserExperience $user_experience){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user_experience->is_locked && $user_experience->user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserExperience::where('id','!=',$user_experience->id)->whereUserId($user->id)->whereCompanyName($request->input('company_name'))->whereFromDate($request->input('from_date'))->whereToDate($request->input('to_date'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.experience')]),'status' => 'error']);

        $upload_validation = updateUpload('user-experience',$user_experience->id,$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $data = $request->all();
        $user_experience->fill($data)->save();
        updateCustomField($this->form,$user_experience->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'updated','sub_module' => 'experience', 'sub_module_id' => $user_experience->id]);

        return response()->json(['message' => trans('messages.experience'), 'status' => 'success']);
    }

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('user-experience')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user_experience = UserExperience::find($upload->module_id);

        if(!$user_experience)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return redirect('/user/'.$user->id)->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/user/'.$user->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function destroy(Request $request, UserExperience $user_experience){
        $user = $user_experience->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user_experience->is_locked && $user_experience->user_id == \Auth::user()->id && count(getParent()))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteUpload('user-experience',$user_experience->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted','sub_module' => 'experience', 'sub_module_id' => $user_experience->id]);

        deleteCustomField($this->form, $user_experience->id);
        $user_experience->delete();

        return response()->json(['message' => trans('messages.experience').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}