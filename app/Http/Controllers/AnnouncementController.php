<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\AnnouncementRequest;
use Entrust;
use App\Announcement;

Class AnnouncementController extends Controller{
    use BasicController;

	protected $form = 'announcement-form';

	public function isAccessible($announcement){

		$accessible = Announcement::whereId($announcement->id)->where(function($query){
			$query->whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->orWhere(function($query1){
				$query1->where(function($query2){
					$query2->where('audience','=','user')->whereHas('user',function($query3){
						$query3->where('user_id','=',\Auth::user()->id);
					});
				})->orWhere(function($query4){
					$query4->where('audience','=','designation')->whereHas('designation',function($query5){
						$query5->where('designation_id','=',\Auth::user()->Profile->designation_id);
					});
				});
			});
		})->count();

		if($accessible)
			return 1;
		else
			return 0;
	}

	public function index(){

		if(!Entrust::can('list-announcement'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.title'),
	        		trans('messages.duration'),
	        		trans('messages.audience'),
	        		trans('messages.user').' '.trans('messages.w_added'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['announcement-table'] = array(
				'source' => 'announcement',
				'title' => trans('messages.announcement').' '.trans('messages.list'),
				'id' => 'announcement_table',
				'data' => $data
			);

		$accessible_users = getAccessibleUserList();
		$accessible_designations = childDesignation();

		$assets = ['datatable','summernote','graph'];
		$menu = 'announcement';
		return view('announcement.index',compact('table_data','assets','menu','accessible_users','accessible_designations'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-announcement'))
			return;

		$announcements = Announcement::where(function($query){
			$query->whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->orWhere(function($query1){
				$query1->where(function($query2){
					$query2->where('audience','=','user')->whereHas('user',function($query3){
						$query3->where('user_id','=',\Auth::user()->id);
					});
				})->orWhere(function($query4){
					$query4->where('audience','=','designation')->whereHas('designation',function($query5){
						$query5->where('designation_id','=',\Auth::user()->Profile->designation_id);
					});
				});
			});
		})->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($announcements as $announcement){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/announcement/'.$announcement->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-announcement') && $announcement->user_id == \Auth::user()->id) ? '<a href="#" data-href="/announcement/'.$announcement->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((Entrust::can('delete-announcement') && $announcement->user_id == \Auth::user()->id) ? delete_form(['announcement.destroy',$announcement->id]) : '').
				'</div>',
				$announcement->title,
				showDate($announcement->from_date).' '.trans('messages.to').' '.showDate($announcement->to_date),
				trans('messages.'.$announcement->audience),
				$announcement->UserAdded->name_with_designation_and_department,
				showDateTime($announcement->created_at)
				);
			$id = $announcement->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }

        $audiences = array();
        $designations = array();
        foreach($announcements as $announcement){
        	$audiences[] = trans('messages.'.$announcement->audience);
        	if($announcement->audience == 'designation')
            foreach($announcement->designation as $designation)
            	$designations[] = $designation->name;
        }

        $list['graph']['announcement_audience'] = getPieCharData($audiences,'audience-wise-announcement-graph');
        $list['graph']['announcement_designation'] = getPieCharData($designations,'designation-wise-announcement-graph');

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function edit(Announcement $announcement){
		if(!Entrust::can('edit-announcement') || $announcement->user_id != \Auth::user()->id)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$accessible_users = getAccessibleUserList();
		$accessible_designations = childDesignation();

		$uploads = editUpload('announcement',$announcement->id);
		$custom_field_values = getCustomFieldValues($this->form,$announcement->id);

        return view('announcement.edit',compact('announcement','accessible_users','accessible_designations','uploads','custom_field_values'));
	}

	public function show(Announcement $announcement){
        if(!$this->isAccessible($announcement))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$uploads = \App\Upload::whereModule('announcement')->whereModuleId($announcement->id)->whereStatus(1)->get();
		$this->updateNotification(['module' => 'announcement','module_id' => $announcement->id]);
        return view('announcement.show',compact('announcement','uploads'));
	}

	public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('announcement')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/announcement')->withErrors(trans('messages.invalid_link'));

        $announcement = Announcement::find($upload->module_id);

        if(!$announcement)
            return redirect('/announcement')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($announcement))
            return redirect('/announcement')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/announcement')->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
	}

	public function store(AnnouncementRequest $request, Announcement $announcement){
		if(!Entrust::can('create-announcement'))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('announcement',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $announcement->fill($data);
	    $announcement->description = clean($request->input('description'),'custom');
        $announcement->user_id = \Auth::user()->id;
        $announcement->save();
        if($request->input('audience') == 'user')
        	$announcement->user()->sync(($request->input('user_id')) ? : []);
        elseif($request->input('audience') == 'designation')
        	$announcement->designation()->sync(($request->input('designation_id')) ? : []);
		$this->logActivity(['module' => 'announcement','module_id' => $announcement->id,'activity' => 'added']);
		storeCustomField($this->form,$announcement->id, $data);
        storeUpload('announcement',$announcement->id,$request);

        if($announcement->audience == 'user')
        	$notification_users = implode(',',$announcement->user()->pluck('user_id')->all());
        else {
        	$notification_designations = $announcement->designation()->pluck('designation_id')->all();
        	$notification_users = \App\User::whereHas('profile',function($query) use($notification_designations){
        		$query->whereIn('designation_id',$notification_designations);
        	})->get()->pluck('id')->all();
        	$notification_users = implode(',', $notification_users);
        }
        $this->sendNotification(['module' => 'announcement','module_id' => $announcement->id,'url' => '/home','user' => $notification_users]);

        return response()->json(['message' => trans('messages.announcement').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(AnnouncementRequest $request, Announcement $announcement){
		if(!Entrust::can('edit-announcement') || $announcement->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        
        $upload_validation = updateUpload('announcement',$announcement->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $data = $request->all();
        $announcement->fill($data);
	    $announcement->description = clean($request->input('description'),'custom');
        $announcement->save();
        if($request->input('audience') == 'user'){
        	$announcement->user()->sync(($request->input('user_id')) ? : []);
        	$announcement->designation()->sync([]);
        }
        elseif($request->input('audience') == 'designation'){
        	$announcement->designation()->sync(($request->input('designation_id')) ? : []);
        	$announcement->user()->sync([]);
        }

		$this->logActivity(['module' => 'announcement','module_id' => $announcement->id,'activity' => 'updated']);
		updateCustomField($this->form,$announcement->id, $data);

        return response()->json(['message' => trans('messages.announcement').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Request $request, Announcement $announcement){
		if(!Entrust::can('delete-announcement') || $announcement->user_id != \Auth::user()->id)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		deleteUpload('announcement',$announcement->id);

		$this->logActivity(['module' => 'announcement','module_id' => $announcement->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $announcement->id);
		$announcement->delete();
        return response()->json(['message' => trans('messages.announcement').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}