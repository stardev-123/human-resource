<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Task;
use Entrust;
use Validator;

Class TaskController extends Controller{
    use BasicController;

    protected $form = 'task-form';

	public function index(){

		if(!Entrust::can('list-task'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.title'),
	        		trans('messages.status'),
	        		trans('messages.category'),
	        		trans('messages.priority'),
	        		trans('messages.progress'),
	        		trans('messages.start').' '.trans('messages.date'),
	        		trans('messages.due').' '.trans('messages.date'),
	        		trans('messages.complete').' '.trans('messages.date'),
	        		trans('messages.user')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['task-table'] = array(
				'source' => 'task',
				'title' => trans('messages.task').' '.trans('messages.list'),
				'id' => 'task_table',
				'data' => $data,
				'form' => 'task-filter-form'
			);

		$task_categories = \App\TaskCategory::all()->pluck('name','id')->all();
		$task_priorities = \App\TaskPriority::all()->pluck('name','id')->all();

        $query = getAccessibleUser();
        $users = $query->get()->pluck('name_with_designation_and_department','id')->all();

		$assets = ['datatable','summernote','tags','slider','graph'];
		$menu = 'task';
		return view('task.index',compact('table_data','assets','menu','task_categories','task_priorities','users'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-task'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$query = $this->fetchTask();

        if($request->has('task_category_id'))
            $query->whereIn('task_category_id',$request->input('task_category_id'));

        if($request->has('task_priority_id'))
            $query->whereIn('task_priority_id',$request->input('task_priority_id'));

        if($request->has('progress'))
        	$query->whereBetween('progress',explode(',',$request->input('progress')));

        if($request->has('user_id'))
        	$query->whereHas('user',function($q) use($request){
        		$q->whereIn('user_id',$request->input('user_id'));
        	});

        if($request->has('type') && $request->input('type') == 'owned')
        	$query->where('user_id',\Auth::user()->id);
        elseif($request->has('type') && $request->input('type') == 'assigned')
        	$query->whereHas('user',function($q){
        		$q->where('user_id',\Auth::user()->id);
        	});

        if($request->has('start_date_start') && $request->has('start_date_end'))
        	$query->whereBetween('start_date',[$request->input('start_date_start'),$request->input('start_date_end')]);

        if($request->has('due_date_start') && $request->has('due_date_end'))
        	$query->whereBetween('due_date',[$request->input('due_date_start'),$request->input('due_date_end')]);

        if($request->has('complete_date_start') && $request->has('complete_date_end'))
        	$query->whereBetween('complete_date',[$request->input('complete_date_start'),$request->input('complete_date_end')]);

        if($request->has('status')){
        	if($request->input('status') == 'unassigned')
        		$query->doesntHave('user');
        	elseif($request->input('status') == 'pending')
        		$query->whereBetween('progress',[0,99])->where('due_date','>',date('Y-m-d'));
        	elseif($request->input('status') == 'complete')
        		$query->where('progress','=',100);
        	elseif($request->input('status') == 'overdue')
        		$query->where('progress','<',100)->where('due_date','<',date('Y-m-d'));
        }

		$tasks = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($tasks as $task){

        	$progress = $task->progress.'% <div class="progress progress-xs" style="margin-top:0px;">
						  <div class="progress-bar progress-bar-'.progressColor($task->progress).'" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$task->progress.'%">
						  </div>
						</div>';

			$status = getTaskStatus($task);

        	$user_list = '<ol>';
        	foreach($task->user as $user)
        		$user_list .= '<li>'.$user->full_name.'</li>';
        	$user_list .= '</ol>';

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="/task/'.$task->uuid.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a>'.
				(($task->StarredTask->where('user_id',\Auth::user()->id)->count()) ? 
					('<a href="#" data-ajax="1" data-extra="&task_id='.$task->id.'" data-source="/task-starred" class="btn btn-xs btn-default"> <i class="fa fa-star starred" data-toggle="tooltip" title="'.trans('messages.remove').' '.trans('messages.favourite').'"></i></a>') : ('<a href="#" data-ajax="1" data-extra="&task_id='.$task->id.'" data-source="/task-starred" class="btn btn-xs btn-default"> <i class="fa fa-star-o" data-toggle="tooltip" title="'.trans('messages.mark').' '.trans('messages.as').' '.trans('messages.favourite').'"></i></a>')).
				(Entrust::can('edit-task') ? '<a href="#" data-href="/task/'.$task->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				(Entrust::can('delete-task') ? delete_form(['task.destroy',$task->id]) : '').
				'</div>',
				$task->title.' '.(($task->sign_off_status == 'approved') ? ('<span class="label label-success">'.trans('messages.sign_off').'</span>') : ''),
				$status,
				$task->TaskCategory->name,
				$task->TaskPriority->name,
				$progress,
				showDate($task->start_date),
				showDate($task->due_date),
				showDateTime($task->complete_date),
				$user_list,
				);
			$id = $task->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;

        $task_categories = array();
        $task_priorities = array();
        $task_statuses = array();
        $departments = array();
        foreach($tasks as $task){

        	if($task->progress < 100)
        		$status = trans('messages.pending');
        	elseif($task->progress == 100)
        		$status = trans('messages.complete');

        	if($task->due_date < date('Y-m-d') && $task->progress < 100)
        		$status = trans('messages.overdue');

            $task_categories[] = $task->TaskCategory->name;
            $task_priorities[] = $task->TaskPriority->name;
            $task_statuses[] = $status;
            if($task->UserAdded->department_name)
            	$departments[] = $task->UserAdded->department_name;
        }

        $list['graph']['task_category'] = getPieCharData($task_categories,'category-wise-task-graph');
        $list['graph']['task_priority'] = getPieCharData($task_priorities,'priority-wise-task-graph');
        $list['graph']['task_status'] = getPieCharData($task_statuses,'status-wise-task-graph');
        $list['graph']['task_department'] = getPieCharData($departments,'department-wise-task-graph');

        return json_encode($list);
	}

	public function fetch(Request $request){

		$query = $this->fetchTask();
		
		if($request->input('type') == 'starred')
			$query->whereHas('starredTask',function($q) use($request){
				$q->where('user_id','=',\Auth::user()->id);
			})->orderBy('start_date','desc');
		elseif($request->input('type') == 'owned')
			$query->where('user_id','=',\Auth::user()->id)->orderBy('start_date','desc');
		elseif($request->input('type') == 'overdue')
			$query->where('progress','<',100)->where('due_date','<',date('Y-m-d'))->orderBy('due_date','asc');
		elseif($request->input('type') == 'pending')
			$query->where('progress','<',100)->orderBy('due_date','asc');
		elseif($request->input('type') == 'unassigned')
			$query->doesntHave('user')->orderBy('start_date','desc');

		$tasks = $query->take(5)->get();

		$type = $request->input('type');

		return view('task.fetch',compact('tasks','type'))->render();
	}

	public function show($uuid){
		$task = Task::whereUuid($uuid)->first();

		if(!$task || !$this->taskAccessible($task->id))
			return redirect('/task')->withErrors(trans('messages.permission_denied'));

		$this->updateNotification(['module' => 'task','module_id' => $task->id]);

		$menu = 'task';
		$assets = ['summernote','tags','slider'];
		return view('task.show',compact('task','assets','menu'));
	}

	public function detail(Request $request){
		$task = Task::find($request->input('id'));

		$status = getTaskStatus($task,'lb-md');

		return view('task.detail',compact('task','status'))->render();
	}

	public function starred(Request $request){
		if(!$this->taskAccessible($request->input('task_id')))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$task = Task::find($request->input('task_id'));

		if($task->StarredTask->where('user_id',\Auth::user()->id)->count())
			\App\StarredTask::whereTaskId($task->id)->whereUserId(\Auth::user()->id)->delete();
		else
			\App\StarredTask::create(['task_id' => $task->id,'user_id' => \Auth::user()->id]);
			
        return response()->json(['status' => 'success']);
	}

	public function description(Request $request){
		$task = Task::find($request->input('id'));

		$uploads = \App\Upload::whereModule('task')->whereModuleId($task->id)->whereStatus(1)->get();

		return view('task.description',compact('task','uploads'));
	}

	public function activity(Request $request){
		$task = Task::find($request->input('id'));

		$activities = \App\Activity::whereModule('task')->whereUniqueId($task->id)->orderBy('created_at','desc')->get();

		return view('task.activity',compact('task','activities'))->render();
	}

	public function comment(Request $request){
		$task = Task::find($request->input('id'));
		return view('task.comment',compact('task'))->render();
	}

	public function progress($id, Request $request){
		if(!$this->taskAccessible($id))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$task = Task::find($id);

		if($task->sign_off_status == 'requested' && $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		if($task->sign_off_status == 'approved')
            return response()->json(['message' => trans('messages.task_sign_off_approved'), 'status' => 'error']);

		$task->progress = $request->input('progress');
		if($request->input('progress') == '100')
			$task->complete_date = new \DateTime;
		else
			$task->complete_date = null;
		$task->save();

		$this->logActivity(['module' => 'task','sub_module' => 'progress','module_id' => $task->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.task').' '.trans('messages.progress').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function edit(Task $task){
		if($task->user_id != \Auth::user()->id || !Entrust::can('edit-task') )
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$task_categories = \App\TaskCategory::all()->pluck('name','id')->all();
		$task_priorities = \App\TaskPriority::all()->pluck('name','id')->all();

        $query = getAccessibleUser();
        $users = $query->get()->pluck('name_with_designation_and_department','id')->all();

		$selected_user = array();

		foreach($task->User as $user)
			$selected_user[] = $user->id;

		$uploads = editUpload('task',$task->id);

        return view('task.edit',compact('task','task_categories','task_priorities','users','selected_user','uploads'));
	}

	public function store(TaskRequest $request, Task $task){
		if(!Entrust::can('create-task'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
	
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('task',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
	    $task->fill($data);
	    $task->uuid = getUuid();
	    $task->description = clean($request->input('description'),'custom');
	    $task->user_id = \Auth::user()->id;
		$task->save();
	    $task->user()->sync(($request->input('user_id')) ? : []);

	    $notification_users = $task->user()->pluck('user_id')->all();
	    $this->sendNotification(['module' => 'task','module_id' => $task->id,'url' => '/task/'.$task->uuid,'user' => implode(',',$notification_users),'action' => 'assign-task']);
	    $top_designation_users = getDirectParentUserId();
	    if($top_designation_users)
	    	$this->sendNotification(['module' => 'task','module_id' => $task->id,'url' => '/task/'.$task->uuid,'user' => implode(',',$top_designation_users),'action' => 'create-task']);

		$this->logActivity(['module' => 'task','module_id' => $task->id,'activity' => 'added']);
		storeCustomField($this->form,$task->id, $data);
        storeUpload('task',$task->id,$request);
        return response()->json(['message' => trans('messages.task').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(TaskRequest $request, Task $task){
		if(!Entrust::can('edit-task') || $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        
        if($task->sign_off_status == 'approved')
            return response()->json(['message' => trans('messages.task_sign_off_approved'), 'status' => 'error']);

        $upload_validation = updateUpload('task',$task->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $task_user = $task->user()->pluck('user_id')->all();
        $requested_task_user = $request->input('user_id');
        $new_task_user = array_diff($requested_task_user, $task_user);

		$data = $request->all();
		$task->fill($data);
	    $task->description = clean($request->input('description'),'custom');
		$task->save();
	    $task->user()->sync(($request->input('user_id')) ? : []);
		updateCustomField($this->form,$task->id, $data);

	    $this->sendNotification(['module' => 'task','module_id' => $task->id,'url' => '/task/'.$task->uuid,'user' => implode(',',$new_task_user),'action' => 'assign-task']);

		$this->logActivity(['module' => 'task','module_id' => $task->id,'activity' => 'updated']);
		
        return response()->json(['message' => trans('messages.task').' '.trans('messages.updated'), 'status' => 'success']);
	}

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('task')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/task')->withErrors(trans('messages.invalid_link'));

        $task = Task::find($upload->module_id);

        if(!$task)
            return redirect('/task')->withErrors(trans('messages.invalid_link'));

        if(!$this->taskAccessible($task->id))
            return redirect('/task')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/task/'.$task->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

	public function destroy(Task $task, Request $request){
		if(!Entrust::can('delete-task') || $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		deleteUpload('task',$task->id);

		$this->logActivity(['module' => 'task','module_id' => $task->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $task->id);
		$task->delete();
        return response()->json(['message' => trans('messages.task').' '.trans('messages.deleted'), 'status' => 'success']);
	}

	public function signOffRequest(Request $request){
		$task = Task::find($request->input('task_id'));

		if($task->sign_off_status != null && $task->sign_off_status != 'rejected' && $task->sign_off_status != 'cancelled')
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$task_sign_off_request = new \App\TaskSignOffRequest;
		$task_sign_off_request->task_id = $task->id;
		$task_sign_off_request->user_id = \Auth::user()->id;
		$task_sign_off_request->remarks = $request->input('remarks');
		$task_sign_off_request->status = 'requested';
		$task_sign_off_request->save();
		$task->sign_off_status = $task_sign_off_request->status;
		$task->save();

		$notification_users = getParentUserId(\Auth::user()->Profile->designation_id);
		$task_members = $task->user()->pluck('user_id')->get();
		$this->sendNotification(['module' => 'task','module_id' => $task->id,'url' => '/task/'.$task->uuid,'user' => implode(',',array_unique(array_merge($notification_users,$task_members))),'action' => 'request-task-sign-off']);

		$this->logActivity(['module' => 'task','sub_module' => 'sign_off','module_id' => $task->id,'activity' => 'requested']);

	    return response()->json(['message' => trans('messages.request').' '.trans('messages.submit'), 'status' => 'success']);
	}

	public function signOffRequestAction(Request $request){
		$task = Task::find($request->input('task_id'));

		if(($task->sign_off_status != 'requested' && $task->sign_off_status != 'approved') || $task->user_id != \Auth::user()->id)
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$task_sign_off_request = new \App\TaskSignOffRequest;
		$task_sign_off_request->task_id = $task->id;
		$task_sign_off_request->user_id = \Auth::user()->id;
		$task_sign_off_request->remarks = $request->input('remarks');
		$task_sign_off_request->status = $request->input('action');
		$task_sign_off_request->save();
		$task->sign_off_status = $task_sign_off_request->status;
		$task->save();

		if($task->sign_off_status == 'rejected')
			$notification_action = 'reject-task-sign-off';
		elseif($task->sign_off_status == 'approved')
			$notification_action = 'approve-task-sign-off';
		elseif($task->sign_off_status == 'cancelled')
			$notification_action = 'cancel-task-sign-off';

		$task_user = $task->user()->pluck('user_id')->get();
		$this->sendNotification(['module' => 'task','module_id' => $task->id,'url' => '/task/'.$task->uuid,'user' => implode(',',$task_user),'action' => $notification_action]);

		$this->logActivity(['module' => 'task','sub_module' => 'sign_off','module_id' => $task->id,'activity' => $task->sign_off_status]);

	    return response()->json(['message' => trans('messages.request').' '.trans('messages.processed'), 'status' => 'success']);
	}

	public function ratingType(Request $request){

		$task = Task::find($request->input('task_id'));

		if($task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$sub_task_rating = $request->input('sub_task_rating');

		$task->sub_task_rating = $sub_task_rating;
		$task->save();
		
		$this->logActivity(['module' => 'task','sub_module' => 'rating_type', 'module_id' => $task->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.configuration').' '.trans('messages.saved'),'status' => 'success','redirect' => '/task/'.$task->id]);
	}

	public function rating($task_id,$user_id){
		$task = Task::find($task_id);

        $valid_user = Task::whereId($task_id)->whereHas('user',function($q) use($user_id){
        	$q->where('user_id',$user_id);
        })->count();

		if($task->sub_task_rating || $task->user_id != \Auth::user()->id || !$valid_user)
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $user_rating = '';
        $user_comment = '';
        foreach($task->user as $user){
        	if($user->id == $user_id){
        		$user_rating = $user->pivot->rating;
        		$user_comment = $user->pivot->comment;
        	}
        }

        $user = \App\User::find($user_id);
		return view('task.rating',compact('task','user','user_rating','user_comment'));
	}

	public function listRating(Request $request){
		$task = Task::find($request->input('task_id'));

		if($task->sub_task_rating || !$task)
			return;

		return view('task.task_rating_list',compact('task'))->render();
	}

	public function storeRating(Request $request, $task_id,$user_id){
		$task = Task::find($task_id);

		if(!$task || $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		if(!$task->sub_task_rating) {
			$validation_rules['rating'] = 'required';

	        $validation = Validator::make($request->all(),$validation_rules);

	        if($validation->fails())
                return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
		}

		if(!$task->sub_task_rating){
	        $task->user()->sync([$user_id => [
					'rating' => $request->input('rating'),
					'comment' => ($request->has('comment')) ? $request->input('comment') : null
				]], false); 
		} else {
			$rating = $request->input('rating');
			$comment = $request->input('comment');
			foreach($task->SubTask as $sub_task){
				$sub_task_rating = \App\SubTaskRating::firstOrNew(['sub_task_id' => $sub_task->id,'user_id' => $user_id]);
				$sub_task_rating->sub_task_id = $sub_task->id;
				$sub_task_rating->user_id = $user_id;
				$sub_task_rating->rating = $rating[$sub_task->id];
				$sub_task_rating->comment = $comment[$sub_task->id];
				$sub_task_rating->save();
			}
		}

		$this->logActivity(['module' => 'task','sub_module' => 'rating', 'module_id' => $task->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.rating').' '.trans('messages.saved'), 'status' => 'success','refresh_table' => ($task->sub_task_rating ? 'sub-task-rating-table' : 'task-rating-table') ,'refresh_content' => 'load-task-activity']);
	}

	public function destroyTaskRating(Request $request){
		$task = Task::find($request->input('task_id'));

		if($task->sub_task_rating || !$task || $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$task->user()->sync([$request->input('user_id') => [
				'rating' => null,
				'comment' => null
			]], false); 

		$this->logActivity(['module' => 'task','sub_module' => 'rating', 'module_id' => $task->id,'activity' => 'deleted']);

        return response()->json(['message' => trans('messages.rating').' '.trans('messages.deleted'), 'status' => 'success','refresh_content' => 'load-task-activity']);
	}

	public function subTaskRating($task_id,$user_id){
		$task = Task::find($task_id);
		$user = \App\User::find($user_id);

        $users = $task->user()->pluck('user_id')->all();

		if(!$task->sub_task_rating || !$task || !$user || $task->user_id != \Auth::user()->id || !in_array($user->id,$users))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        if(!$task->SubTask->count())
            return view('global.error',['message' => trans('messages.no_sub_task_found')]);

        return view('task.sub_task_rating',compact('task','user'));
	}

	public function listSubTaskRating(Request $request){
		$task = Task::find($request->input('task_id'));

		if(!$task->sub_task_rating || !$task)
			return;

		return view('task.sub_task_rating_list',compact('task'))->render();
	}

	public function showSubTaskRating($task_id,$user_id){
		$task = \App\Task::find($task_id);
        $user = \App\User::find($user_id);

        $users = $task->user()->pluck('user_id')->all();

		if(!$task->sub_task_rating || !$task || !$user || $task->user_id != \Auth::user()->id || !in_array($user->id,$users))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        return view('task.sub_task_rating_view',compact('task','user'));
	}

	public function destroySubTaskRating(Request $request){
		$task = Task::find($request->input('task_id'));

		if(!$task->sub_task_rating || !$task || $task->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$sub_tasks = $task->SubTask->pluck('id')->all();

		$sub_task_rating = \App\SubTaskRating::whereIn('sub_task_id',$sub_tasks)->whereUserId($request->input('user_id'))->get();

		if(!$sub_task_rating->count())
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

		\App\SubTaskRating::whereIn('sub_task_id',$sub_tasks)->whereUserId($request->input('user_id'))->delete();

		$this->logActivity(['module' => 'task','sub_module' => 'rating', 'module_id' => $task->id,'activity' => 'deleted']);

        return response()->json(['message' => trans('messages.rating').' '.trans('messages.deleted'), 'status' => 'success','refresh_content' => 'load-task-activity']);
	}

	public function userTaskRating(){

        $data = array(
        		trans('messages.user'),
        		trans('messages.total').' '.trans('messages.task'),
        		trans('messages.complete').' '.trans('messages.task'),
        		trans('messages.overdue').' '.trans('messages.task'),
        		trans('messages.rating')
        		);

        $menu = 'report,user_task_rating';
        $table_data['user-task-rating-table'] = array(
			'source' => 'user-task-rating',
			'title' => 'User Task Rating',
			'id' => 'user-task-rating-table',
			'form' => 'user-task-rating-form',
			'data' => $data
		);

        $designations = \App\Designation::whereIn('id',getDesignation(\Auth::user(),1))->get()->pluck('designation_with_department','id')->all();
        $locations = \App\Location::whereIn('id',getLocation(\Auth::user(),1))->get()->pluck('name','id')->all();
		$assets = ['datatable'];

		return view('task.user_task_rating',compact('table_data','menu','assets','designations','locations'));
	}

	public function userTaskRatingLists(Request $request){

        $rows=array();

        $query = getAccessibleUser();

        if($request->has('designation_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('designation_id',$request->input('designation_id'));
            });
        
        if($request->has('location_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('location_id',$request->input('location_id'));
            });

        $users = $query->get();

		foreach($users as $user){

			$rating = 0;
			$completed_task = $user->Task->where('progress','100')->count();
			$overdue_task = $user->Task->filter(function($item){
					return (data_get($item, 'progress') < '100');
				})->filter(function($item) {
					return (data_get($item, 'due_date') < date('Y-m-d'));
				})->count();
			$total_task = $user->Task->count();
			foreach($user->Task as $task){
				if($task->sub_task_rating)
					$rating += getSubTaskRating($task->id,$user->id,1);
				else
					$rating += $task->pivot->rating;
			}

			$average_rating = ($total_task) ? $rating/$total_task : 0;

			$rows[] = array(
				$user->name_with_designation_and_department,
				$total_task,
				$completed_task,
				$overdue_task,
				getRatingStar($average_rating)
			);
		}
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function userTaskSummary(){

        $query = getAccessibleUser();

        $users = $query->get()->pluck('name_with_designation_and_department','id')->all();

        $data = array(
        		trans('messages.option'),
        		trans('messages.title'),
        		trans('messages.category'),
        		trans('messages.priority'),
        		trans('messages.start').' '.trans('messages.date'),
        		trans('messages.due').' '.trans('messages.date'),
        		trans('messages.complete').' '.trans('messages.date'),
        		trans('messages.progress'),
        		trans('messages.rating')
        		);

        $menu = 'report,user_task_summary';
        $table_data['user-task-summary-table'] = array(
			'source' => 'user-task-summary',
			'title' => 'User Task Summary',
			'id' => 'user-task-summary-table',
			'form' => 'user-task-summary-form',
			'data' => $data
		);

		$task_categories = \App\TaskCategory::all()->pluck('name','id')->all();
		$task_priorities = \App\TaskPriority::all()->pluck('name','id')->all();

		$assets = ['datatable','slider'];

		return view('task.user_task_summary',compact('table_data','menu','assets','users','task_categories','task_priorities'));
	}

	public function userTaskSummaryLists(Request $request){
		$query = Task::whereHas('user',function($q) use($request){
			$q->where('user_id',$request->input('user_id'));
		});

        if($request->has('task_category_id'))
            $query->whereIn('task_category_id',$request->input('task_category_id'));

        if($request->has('task_priority_id'))
            $query->whereIn('task_priority_id',$request->input('task_priority_id'));

        if($request->has('progress'))
        	$query->whereBetween('progress',explode(',',$request->input('progress')));


        if($request->has('start_date_start') && $request->has('start_date_end'))
        	$query->whereBetween('start_date',[$request->input('start_date_start'),$request->input('start_date_end')]);

        if($request->has('due_date_start') && $request->has('due_date_end'))
        	$query->whereBetween('due_date',[$request->input('due_date_start'),$request->input('due_date_end')]);

        if($request->has('complete_date_start') && $request->has('complete_date_end'))
        	$query->whereBetween('complete_date',[$request->input('complete_date_start'),$request->input('complete_date_end')]);

        $tasks = $query->get();

        $rows=array();

        foreach($tasks as $task){

        	$progress = $task->progress.'% <div class="progress progress-xs" style="margin-top:0px;">
						  <div class="progress-bar progress-bar-'.progressColor($task->progress).'" role="progressbar" aria-valuenow="'.$task->progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$task->progress.'%">
						  </div>
						</div>';

			if($task->sub_task_rating)
				$rating = getSubTaskRating($task->id,$request->input('user_id'),1);
			else {
				$rating = null;
				foreach($task->User as $user)
					if($user->id == $request->input('user_id'))
						$rating = $user->pivot->rating;
			}

        	$rows[] = array(
        		'<div class="btn-group btn-group-xs">'.
					'<a href="/task/'.$task->id.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a>
				</div>',
        		$task->title,
        		$task->TaskCategory->name,
        		$task->TaskPriority->name,
        		showDate($task->start_date),
        		showDate($task->due_date),
        		showDateTime($task->complete_date),
        		$progress,
        		getRatingStar($rating)
        		);
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function topChart(){

		$chart = array();
		foreach(\App\User::all() as $user){
			$rating = 0;
			$total_task = $user->Task->count();
			foreach($user->Task as $task){
				if($task->sub_task_rating)
					$rating += getSubTaskRating($task->id,$user->id,1);
				else
					$rating += $task->pivot->rating;
			}

			$average_rating = ($total_task) ? $rating/$total_task : 0;

			$chart[] = array('rating' => $average_rating,
								'id' => $user->id,
								'name' => $user->name_with_designation_and_department,
								'task' => $user->Task->count()
								);
		}

		usort($chart, function($a, $b) {
		    if($a['rating']==$b['rating']) return 0;
		    return $a['rating'] < $b['rating']?1:-1;
		});

		$i = 0;
		$j = 1;
		$top_chart = array();
		foreach($chart as $key => $value){
			$i++;
			if($i <= 5 && $value['rating']){
				$value['rank'] = $j;
				$top_chart[] = $value;
				$j++;
			}

		}

		return view('task.top_chart',compact('top_chart'));
	}
}