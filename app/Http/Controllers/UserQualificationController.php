<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserQualificationRequest;
use Entrust;
use App\UserQualification;

Class UserQualificationController extends Controller{
    use BasicController;

    protected $form = 'user-qualification-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        $self_service = (config('config.user_manage_own_qualification')) ? 1 : 0;

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

        return view('user_qualification.list',compact('user'))->render();
    }

    public function toggleLock(Request $request){
        $user_qualification = UserQualification::find($request->input('id'));

        if(!$user_qualification)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if(($user_qualification->user_id == \Auth::user()->id && count(getParent())) || ($user_qualification->user_id != \Auth::user()->id && !Entrust::can('edit-user')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $user_qualification->is_locked = ($user_qualification->is_locked) ? 0 : 1;
        $user_qualification->save();

        return response()->json(['message' => '', 'status' => 'success']);
    }

    public function show(UserQualification $user_qualification){
        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();
        $uploads = \App\Upload::whereModule('user-qualification')->whereModuleId($user_qualification->id)->whereStatus(1)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_qualification.show',compact('user','user_qualification','values','col_ids','custom_fields','uploads'));
    }

	public function store(UserQualificationRequest $request,$user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserQualification::whereUserId($user->id)->whereInstituteName($request->input('institute_name'))->whereEducationLevelId($request->input('education_level_id'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.qualification')]),'status' => 'error']);

        $upload_validation = validateUpload('user-qualification',$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $user_qualification = new UserQualification;
        $data = $request->all();
	    $user_qualification->fill($data);
        $user_qualification->save();
        $user->userQualification()->save($user_qualification);
        storeUpload('user-qualification',$user_qualification->id,$request);
        storeCustomField($this->form,$user_qualification->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'added','sub_module' => 'qualification', 'sub_module_id' => $user_qualification->id]);

        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.added'), 'status' => 'success']);
	}

    public function edit(UserQualification $user_qualification){
        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if($user_qualification->is_locked && $user_qualification->user_id == \Auth::user()->id && count(getParent()))
            return view('global.error',['message' => trans('messages.permission_denied')]);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $education_levels = \App\EducationLevel::all()->pluck('name','id')->all();
        $qualification_languages = \App\QualificationLanguage::all()->pluck('name','id')->all();
        $qualification_skills = \App\QualificationSkill::all()->pluck('name','id')->all();
        $custom_user_qualification_field_values = getCustomFieldValues($this->form,$user_qualification->id);

        $uploads = editUpload('user-qualification',$user_qualification->id);
        return view('user_qualification.edit',compact('user_qualification','custom_user_qualification_field_values','education_levels','uploads','qualification_languages','qualification_skills'));
    }

    public function update(UserQualificationRequest $request, UserQualification $user_qualification){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_qualification->is_locked && $user_qualification->user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserQualification::where('id','!=',$user_qualification->id)->whereUserId($user->id)->whereInstituteName($request->input('institute_name'))->whereEducationLevelId($request->input('education_level_id'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.qualification')]),'status' => 'error']);

        $upload_validation = updateUpload('user-qualification',$user_qualification->id,$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $data = $request->all();
        $user_qualification->fill($data)->save();
        updateCustomField($this->form,$user_qualification->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'updated','sub_module' => 'qualification', 'sub_module_id' => $user_qualification->id]);

        return response()->json(['message' => trans('messages.qualification'), 'status' => 'success']);
    }

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('user-qualification')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user_qualification = UserQualification::find($upload->module_id);

        if(!$user_qualification)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return redirect('/user/'.$user->id)->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/user/'.$user->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function destroy(Request $request, UserQualification $user_qualification){
        $user = $user_qualification->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_qualification->is_locked && $user_qualification->user_id == \Auth::user()->id && count(getParent()))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteUpload('user-qualification',$user_qualification->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted','sub_module' => 'qualification', 'sub_module_id' => $user_qualification->id]);

        deleteCustomField($this->form, $user_qualification->id);
        $user_qualification->delete();

        return response()->json(['message' => trans('messages.qualification').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}