<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\UserDocumentRequest;
use Entrust;
use App\UserDocument;

Class UserDocumentController extends Controller{
    use BasicController;

    protected $form = 'user-document-form';

    public function accessible($user){
        if(!$user)
            return ['message' => trans('messages.invalid_link'),'status' => 'error'];

        $self_service = (config('config.user_manage_own_document')) ? 1 : 0;

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

        return view('user_document.list',compact('user'))->render();
    }

    public function show(UserDocument $user_document){
        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        $custom_fields = \App\CustomField::whereForm($this->form)->get();
        $uploads = \App\Upload::whereModule('user-document')->whereModuleId($user_document->id)->whereStatus(1)->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        return view('user_document.show',compact('user','user_document','values','col_ids','custom_fields','uploads'));
    }

    public function toggleLock(Request $request){
        $user_document = UserDocument::find($request->input('id'));

        if(!$user_document)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if(($user_document->user_id == \Auth::user()->id && count(getParent())) || ($user_document->user_id != \Auth::user()->id && !Entrust::can('edit-user')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $user_document->is_locked = ($user_document->is_locked) ? 0 : 1;
        $user_document->save();

        return response()->json(['message' => '', 'status' => 'success']);
    }

	public function store(UserDocumentRequest $request,$user_id){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \App\User::find($user_id);

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserDocument::whereUserId($user->id)->whereDocumentTypeId($request->input('document_type_id'))->whereTitle($request->input('title'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.document')]),'status' => 'error']);

        $upload_validation = validateUpload('user-document',$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $user_document = new UserDocument;
        $data = $request->all();
	    $user_document->fill($data);
        $user_document->save();
        $user->userDocument()->save($user_document);
        storeUpload('user-document',$user_document->id,$request);
        storeCustomField($this->form,$user_document->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'added','sub_module' => 'document', 'sub_module_id' => $user_document->id]);

        return response()->json(['message' => trans('messages.document').' '.trans('messages.added'), 'status' => 'success']);
	}

    public function edit(UserDocument $user_document){
        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return view('global.error',['message' => $accessible['message']]);

        if($user_document->is_locked && $user_document->user_id == \Auth::user()->id && count(getParent()))
            return view('global.error',['message' => trans('messages.permission_denied')]);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $document_types = \App\DocumentType::all()->pluck('name','id')->all();
        $custom_user_document_field_values = getCustomFieldValues($this->form,$user_document->id);
        
        $uploads = editUpload('user-document',$user_document->id);
        return view('user_document.edit',compact('user_document','custom_user_document_field_values','document_types','uploads'));
    }

    public function update(UserDocumentRequest $request, UserDocument $user_document){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_document->is_locked && $user_document->user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(UserDocument::where('id','!=',$user_document->id)->whereUserId($user->id)->whereDocumentTypeId($request->input('document_type_id'))->whereTitle($request->input('title'))->count())
            return response()->json(['message' => trans('messages.validation_unique',['attribute' => trans('messages.document')]),'status' => 'error']);

        $upload_validation = updateUpload('user-document',$user_document->id,$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $data = $request->all();
        $user_document->fill($data)->save();
        updateCustomField($this->form,$user_document->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'updated','sub_module' => 'document', 'sub_module_id' => $user_document->id]);

        return response()->json(['message' => trans('messages.document'), 'status' => 'success']);
    }

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('user-document')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user_document = UserDocument::find($upload->module_id);

        if(!$user_document)
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return redirect('/user/'.$user->id)->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/user/'.$user->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function destroy(Request $request, UserDocument $user_document){
        $user = $user_document->User;

        $accessible = $this->accessible($user);
        
        if($accessible['status'] != 'success')
            return response()->json(['message' => $accessible['message'], 'status' => 'error']);

        if($user_document->is_locked && $user_document->user_id == \Auth::user()->id && count(getParent()))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
        
        deleteUpload('user-document',$user_document->id);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted','sub_module' => 'document', 'sub_module_id' => $user_document->id]);

        deleteCustomField($this->form, $user_document->id);
        $user_document->delete();

        return response()->json(['message' => trans('messages.document').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}