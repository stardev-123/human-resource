<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\LibraryRequest;
use Entrust;
use App\Library;

Class LibraryController extends Controller{
    use BasicController;

	protected $form = 'library-form';

	public function isAccessible($library){

		$accessible = Library::all()->count();

		if($accessible)
			return 1;
		else
			return 0;
	}

	public function index(){

		if(!Entrust::can('list-library'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.title'),
	        		trans('messages.user').' '.trans('messages.w_added'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['library-table'] = array(
				'source' => 'library',
				'title' => trans('messages.library').' '.trans('messages.list'),
				'id' => 'library_table',
				'data' => $data
			);

		$accessible_users = getAccessibleUserList();
		$accessible_designations = childDesignation();

		$assets = ['datatable','summernote','graph'];
		$menu = 'library';
		return view('library.index',compact('table_data','assets','menu','accessible_users','accessible_designations'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-library'))
			return;

		$librarys = Library::all();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($librarys as $library){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/library/'.$library->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-library') && $library->user_id == \Auth::user()->id) ? '<a href="#" data-href="/library/'.$library->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((Entrust::can('delete-library') && $library->user_id == \Auth::user()->id) ? delete_form(['library.destroy',$library->id]) : '').
				'</div>',
				$library->title,
				$library->UserAdded->name_with_designation_and_department,
				showDateTime($library->created_at)
				);
			$id = $library->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }

        $audiences = array();
        $designations = array();
        foreach($librarys as $library){
        	$audiences[] = trans('messages.'.$library->audience2);
        	if($library->audience2 == 'designation')
            foreach($library->designation as $designation)
            	$designations[] = $designation->name;
        }

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function edit(Library $library){
		if(!Entrust::can('edit-library') || $library->user_id != \Auth::user()->id)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$accessible_users = getAccessibleUserList();
		$accessible_designations = childDesignation();

		$uploads = editUpload('library',$library->id);
		$custom_field_values = getCustomFieldValues($this->form,$library->id);

        return view('library.edit',compact('library','accessible_users','accessible_designations','uploads','custom_field_values'));
	}

	public function show(Library $library){
        if(!$this->isAccessible($library))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$uploads = \App\Upload::whereModule('library')->whereModuleId($library->id)->whereStatus(1)->get();
		$this->updateNotification(['module' => 'library','module_id' => $library->id]);
        return view('library.show',compact('library','uploads'));
	}

	public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('library')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/library')->withErrors(trans('messages.invalid_link'));

        $library = Library::find($upload->module_id);

        if(!$library)
            return redirect('/library')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($library))
            return redirect('/library')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/library')->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
	}

	public function store(LibraryRequest $request, Library $library){
		if(!Entrust::can('create-library'))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('library',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $library->fill($data);
	    $library->description = clean($request->input('description'),'custom');
        $library->user_id = \Auth::user()->id;
        $library->save();
        if($request->input('audience2') == 'user')
        	$library->user()->sync(($request->input('user_id')) ? : []);
        elseif($request->input('audience2') == 'designation')
        	$library->designation()->sync(($request->input('designation_id')) ? : []);
		$this->logActivity(['module' => 'library','module_id' => $library->id,'activity' => 'added']);
		storeCustomField($this->form,$library->id, $data);
        storeUpload('library',$library->id,$request);

        if($library->audience2 == 'user')
        	$notification_users = implode(',',$library->user()->pluck('user_id')->all());
        else {
        	$notification_designations = $library->designation()->pluck('designation_id')->all();
        	$notification_users = \App\User::whereHas('profile',function($query) use($notification_designations){
        		$query->whereIn('designation_id',$notification_designations);
        	})->get()->pluck('id')->all();
        	$notification_users = implode(',', $notification_users);
        }
        $this->sendNotification(['module' => 'library','module_id' => $library->id,'url' => '/home','user' => $notification_users]);

        return response()->json(['message' => trans('messages.library').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(LibraryRequest $request, Library $library){
		if(!Entrust::can('edit-library') || $library->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = updateUpload('library',$library->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $library->fill($data);
	    $library->description = clean($request->input('description'),'custom');
        $library->save();
        if($request->input('audience2') == 'user'){
        	$library->user()->sync(($request->input('user_id')) ? : []);
        	$library->designation()->sync([]);
        }
        elseif($request->input('audience2') == 'designation'){
        	$library->designation()->sync(($request->input('designation_id')) ? : []);
        	$library->user()->sync([]);
        }

		$this->logActivity(['module' => 'library','module_id' => $library->id,'activity' => 'updated']);
		updateCustomField($this->form,$library->id, $data);

        return response()->json(['message' => trans('messages.library').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Request $request, Library $library){
		if(!Entrust::can('delete-library') || $library->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		deleteUpload('library',$library->id);

		$this->logActivity(['module' => 'library','module_id' => $library->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $library->id);
		$library->delete();
        return response()->json(['message' => trans('messages.library').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
