<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveRequest;
use Entrust;
use App\Leave;

Class LeaveController extends Controller{
    use BasicController;

	protected $form = 'leave-form';

	public function isAccessible($leave){
		if($leave->user_id == \Auth::user()->id)
			return 1;
		elseif(in_array($leave->user_id,getAccessibleUserId()))
			return 1;
		elseif(in_array(\Auth::user()->Profile->designation_id,\App\LeaveStatusDetail::whereLeaveId($leave->id)->get()->pluck('designation_id')->all()))
			return 1;
		else
			return 0;
	}

	public function status($leave){
		if($leave->status == 'pending' || $leave->status == null)
			return '<span class="label label-info">'.trans('messages.pending').'</span>';
		elseif($leave->status == 'approved')
			return '<span class="label label-success">'.trans('messages.w_approved').'</span>';
		elseif($leave->status == 'rejected')
			return '<span class="label label-danger">'.trans('messages.w_rejected').'</span>';
	}

	public function isEditable($leave){
		$leave_status_detail = $leave->LeaveStatusDetail->first();
		if($leave_status_detail && $leave_status_detail->status == 'pending' && $leave->user_id == \Auth::user()->id)
			return 1;
		else
			return 0;
	}

	public function index(Leave $leave){

		if(!Entrust::can('list-leave'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.user'),
	        		trans('messages.leave').' '.trans('messages.type'),
	        		trans('messages.duration'),
	        		trans('messages.date').' '.trans('messages.w_requested'),
	        		trans('messages.status'),
	        		trans('messages.date').' '.trans('messages.w_approved')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['leave-table'] = array(
				'source' => 'leave',
				'title' => trans('messages.leave').' '.trans('messages.list'),
				'id' => 'leave_table',
				'data' => $data,
				'form' => 'leave-filter-form'
			);

		$leave_types = \App\LeaveType::all()->pluck('name','id')->all();
		$accessible_users = getAccessibleUserList(\Auth::user()->id,1);

		$assets = ['datatable','graph'];
		$menu = 'leave';
		return view('leave.index',compact('table_data','assets','menu','leave_types','accessible_users'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-leave'))
			return;

		$leave_status_details = \App\LeaveStatusDetail::whereDesignationId(\Auth::user()->Profile->designation_id)->get()->pluck('leave_id')->all();

		$accessible_user_leaves = Leave::whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->get()->pluck('id')->all();

		$all_leaves = array_unique(array_merge($leave_status_details,$accessible_user_leaves));
		$query = Leave::whereIn('id',$all_leaves);
		
		if($request->has('user_id'))
			$query->whereIn('user_id',$request->input('user_id'));

		if($request->has('status'))
			$query->whereIn('status',$request->input('status'));

		if($request->has('leave_type_id'))
			$query->whereIn('leave_type_id',$request->input('leave_type_id'));

        if($request->has('date_start') && $request->has('date_end'))
        	$query->where('from_date','<=',$request->input('date_start'))->where('to_date','>=',$request->input('date_end'));

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

        $leaves = $query->get();
        
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($leaves as $leave){

        	if($leave->status == 'approved')
        		$date_approved = count(explode(',',$leave->date_approved)).' '.trans('messages.day');
        	else
        		$date_approved = '';

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="/leave/'.$leave->uuid.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-leave') && $this->isEditable($leave)) ? '<a href="#" data-href="/leave/'.$leave->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((Entrust::can('delete-leave') && $this->isEditable($leave) && $leave->status != 'pending') ? delete_form(['leave.destroy',$leave->id]) : '').
				'</div>',
				$leave->User->name_with_designation_and_department,
				$leave->LeaveType->name,
				showDate($leave->from_date).' '.trans('messages.to').' '.showDate($leave->to_date),
				dateDiff($leave->from_date,$leave->to_date).' '.trans('messages.day'),
				$this->status($leave),
				$date_approved
				);
			$id = $leave->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }

        $statuses = array();
        $types = array();
        $departments = array();
        $locations = array();
        foreach($leaves as $leave){
            if($leave->status)
                $statuses[] = \Lang::has('messages.w_'.$leave->status) ? trans('messages.w_'.$leave->status) : toWordTranslate($leave->status);
            if($leave->leave_type_id)
                $types[] = $leave->LeaveType->name;
            if($leave->User->department_name)
                $departments[] = $leave->User->department_name;
            if($leave->User->location_name)
                $locations[] = $leave->User->location_name;
        }

        $list['graph']['leave_status'] = getPieCharData($statuses,'leave-status-wise-graph');
        $list['graph']['leave_type'] = getPieCharData($types,'leave-type-wise-graph');
        $list['graph']['leave_department'] = getPieCharData($departments,'department-wise-leave-graph');
        $list['graph']['leave_location'] = getPieCharData($locations,'location-wise-leave-graph');

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function leaveStatusDetail(Request $request){
		$leave = Leave::find($request->input('id'));

		if(!$this->isAccessible($leave))
			return;

		return view('leave.status_detail',compact('leave'));
	}

	public function currentStatus(Request $request){
      	$user_id = $request->input('user_id') ? : \Auth::user()->id;
      	$leave_types = \App\LeaveType::all();
      	$date = $request->input('date') ? : date('Y-m-d');

      	$user_leave = \App\UserLeave::whereUserId($user_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->first();

      	if(!$user_leave)
      		return;

      	$user_leave_data = array();
      	foreach($leave_types as $leave_type){
      		$leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first();
      		$leave_used = ($leave_detail) ? $leave_detail->leave_used : 0;
      		$leave_assigned = ($leave_detail) ? $leave_detail->leave_assigned : 0;
      		$leave_used_percentage = ($leave_assigned) ? ($leave_used/$leave_assigned) * 100 : 0;
      		$leave_color = progressColor($leave_used_percentage);

      		$user_leave_data[$leave_type->id] = array(
      			'leave_used' => $leave_used,
      			'leave_assigned' => $leave_assigned,
      			'leave_used_percentage' => $leave_used_percentage,
      			'leave_color' => $leave_color,
      			'leave_name' => $leave_type->name
      		);
      	}

      	$leaves = Leave::whereUserId($user_id)->get();
		return view('leave.current_status',compact('leaves','leave_types','date','user_leave_data','user_leave'))->render();
	}

	public function edit(Leave $leave){
		if(!Entrust::can('edit-leave') || !$this->isEditable($leave))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$leave_types = \App\LeaveType::all()->pluck('name','id')->all();
		$uploads = editUpload('leave',$leave->id);

        return view('leave.edit',compact('leave','leave_types','uploads'));
	}

	public function show($uuid){
		$leave = Leave::whereUuid($uuid)->first();

		if(!$this->isAccessible($leave))
			return redirect('/leave')->withErrors(trans('messages.permission_denied'));

		$menu = 'leave';

        $leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();
        $leave_status_enabled = $this->getLeaveStatus($leave);

        $available_date = getDateInArray($leave->from_date,$leave->to_date);
        $this->updateNotification(['module' => 'leave','module_id' => $leave->id]);

		return view('leave.show',compact('leave','menu','leave_status_detail','leave_status_enabled','available_date'));
	}

	public function detail(Request $request){
		$leave = Leave::find($request->input('id'));

		if(!$leave || !$this->isAccessible($leave))
			return redirect('/leave')->withErrors(trans('messages.permission_denied'));

		$uploads = \App\Upload::whereModule('leave')->whereModuleId($leave->id)->whereStatus(1)->get();

		$status = $this->status($leave);

		return view('leave.detail',compact('leave','uploads','status'))->render();
	}

	public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('leave')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/leave')->withErrors(trans('messages.invalid_link'));

        $leave = Leave::find($upload->module_id);

        if(!$leave)
            return redirect('/leave')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($leave))
            return redirect('/leave')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/leave/'.$leave->uuid)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
	}

	public function getLeaveStatus($leave){

        $leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();

        if($leave_status_detail){
        	$previous_leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->where('id','<',$leave_status_detail->id)->orderBy('id','desc')->first();
        	$next_leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->where('id','>',$leave_status_detail->id)->first();

	        $previous_leave_status = ($previous_leave_status_detail) ? $previous_leave_status_detail->status : null;
	        $next_leave_status = ($next_leave_status_detail) ? $next_leave_status_detail->status : null;
        }

		$last_leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->orderBy('id','desc')->first();

		if(!$leave_status_detail)
			$leave_status_enabled = 0;
		elseif($previous_leave_status == 'rejected' || $previous_leave_status == 'pending')
			$leave_status_enabled = 0;
		elseif($last_leave_status_detail && $last_leave_status_detail->designation_id == \Auth::user()->Profile->designation_id)
			$leave_status_enabled = 1;
		elseif($next_leave_status == 'pending' || $next_leave_status == null)
			$leave_status_enabled = 1;
		elseif($leave_status_detail == 'pending' || $leave_status_detail == null)
			$leave_status_enabled = 1;
		else
			$leave_status_enabled = 0;

		return $leave_status_enabled;
	}

	public function updateStatus(Request $request, $id){

		$leave = Leave::find($id);

		if(!$leave)
			return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

		$leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();

		if(!$this->isAccessible($leave) && !$leave_status_detail)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		$previous_leave_status = $leave_status_detail->status;

        $leave_status_enabled = $this->getLeaveStatus($leave);

        if($leave_status_enabled == 0)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $user_leave = \App\UserLeave::whereUserId($leave->user_id)->where('from_date','<=',$leave->from_date)->where('to_date','>=',$leave->to_date)->first();

        if(!$user_leave)
            return response()->json(['message' => trans('messages.leave_not_defined'), 'status' => 'error']);

        $user_leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id','=',$leave->leave_type_id)->first();

		$date_requested = getDateInArray($leave->from_date,$leave->to_date);
		$date_approved = $request->has('date_approved') ? explode(',',$request->input('date_approved')) : $date_requested;

		if($request->input('status') == 'approved' && !count(array_intersect($date_requested, $date_approved)))
            return response()->json(['message' => trans('messages.invalid_date_approved'), 'status' => 'error']);

		if($request->input('status') == 'pending' || $request->input('status') == 'rejected')
			$date_approved = [];

		$previous_date_approved = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
		$adjustable_date = count($date_approved) - count($previous_date_approved);
		$leave_balance = $user_leave_detail->leave_assigned - $user_leave_detail->leave_used;

		if($adjustable_date > 0 && $leave_balance < $adjustable_date && $request->input('status') == 'approved')
            return response()->json(['message' => trans('messages.low_leave_balance',['balance' => $leave_balance,'name' => $leave->LeaveType->name,'date1' => showDate($user_leave->from_date),'date2' => showDate($user_leave->to_date)]), 'status' => 'error']);

		$leave_status_detail->status = $request->input('status');
		$leave_status_detail->remarks = $request->input('remarks');
		$leave_status_detail->date_approved = count($date_approved) ? implode(',',$date_approved) : null;
		$leave_status_detail->save();

        $next_leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->where('id','>',$leave_status_detail->id)->first();
        if($next_leave_status_detail){
        	$next_leave_status_detail->status = ($request->input('status') == 'pending') ? null : 'pending';
        	$next_leave_status_detail->save();
        }
		$last_leave_status_detail = \App\LeaveStatusDetail::whereLeaveId($leave->id)->orderBy('id','desc')->first();

        if($request->input('status') == 'rejected'){
        	$leave->status = 'rejected';
        	$leave->date_approved = null;
        	$leave->save();
        	\App\LeaveStatusDetail::where('id','>',$leave_status_detail->id)->update(['status' => null]);
        } else {
        	$leave->status = 'pending';
        	$leave->date_approved = null;
        	$leave->save();
        }

		if($last_leave_status_detail && $last_leave_status_detail->designation_id == \Auth::user()->Profile->designation_id){
			if($request->input('status') == 'approved')
				$user_leave_detail->increment('leave_used',$adjustable_date);
			else
				$user_leave_detail->decrement('leave_used',count($previous_date_approved));

			$leave->status = ($request->input('status')) ? : 'pending';
			$leave->date_approved = count($date_approved) ? implode(',',$date_approved) : null;
			$leave->save();
		}

		if($previous_leave_status != $leave_status_detail->status){
			if($leave_status_detail->status == 'rejected'){
				$this->sendNotification(['module' => 'leave','module_id' => $leave->id,'url' => '/leave/'.$leave->uuid,'user' => $leave->user_id,'action' => 'reject-leave']);
			} elseif($leave_status_detail->status == 'approved'){
				if($leave->status != 'approved' && $next_leave_status_detail){
					$notification_users = implode(',',getUserFromDesignation($next_leave_status_detail->designation_id));
					$this->sendNotification(['module' => 'leave','module_id' => $leave->id,'url' => '/leave/'.$leave->uuid,'user' => $leave->user_id,'action' => 'partially-approve-leave']);
					$this->sendNotification(['module' => 'leave','module_id' => $leave->id,'url' => '/leave/'.$leave->uuid,'user' => $notification_users,'action' => 'request-leave']);
				} elseif($leave->status == 'approved'){
					$this->sendNotification(['module' => 'leave','module_id' => $leave->id,'url' => '/leave/'.$leave->uuid,'user' => $leave->user_id,'action' => 'approve-leave']);
				}
			}
		}
		
		$this->logActivity(['leave' => 'leave','module_id' => $leave->id,'activity' => 'status_updated']);

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.status').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function store(LeaveRequest $request, Leave $leave){
		if(!Entrust::can('request-leave'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
	
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('leave',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        $user_leave = \App\UserLeave::whereUserId(\Auth::user()->id)->where('from_date','<=',$request->input('from_date'))->where('to_date','>=',$request->input('to_date'))->first();

        if(!$user_leave)
            return response()->json(['message' => trans('messages.leave_not_defined'), 'status' => 'error']);

        $leave_request_duration = dateDiff($request->input('from_date'),$request->input('to_date'));
        $user_leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$request->input('leave_type_id'))->first();

        $leave_assigned = ($user_leave_detail) ? $user_leave_detail->leave_assigned : 0;
        $leave_used = ($user_leave_detail) ? $user_leave_detail->leave_used : 0;
        $leave_balance = $leave_assigned - $leave_used;

        if($leave_balance < $leave_request_duration)
            return response()->json(['message' => trans('messages.low_leave_balance',['balance' => $leave_balance,'name' => $user_leave_detail->LeaveType->name,'date1' => showDate($user_leave->from_date),'date2' => showDate($user_leave->to_date)]), 'status' => 'error']);

        $other_leaves = Leave::whereUserId(\Auth::user()->id)->get();
        $all_leave_days = array();
        foreach($other_leaves as $other_leave){
        	$leave_days = getDateInArray($other_leave->from_date,$other_leave->to_date);
        	foreach($leave_days as $leave_day)
        		$all_leave_days[] = $leave_day;
        	unset($leave_days);
        }

        $leave_collapse = array_intersect($all_leave_days, getDateInArray($request->input('from_date'),$request->input('to_date')));

		if(count($leave_collapse))
            return response()->json(['message' => trans('messages.leave_already_requested_for_some_duration'), 'status' => 'error']);

		$data = $request->all();
	    $leave->fill($data);
	    $leave->uuid = getUuid();
	    $leave->status = 'pending';
		$leave->user_id = \Auth::user()->id;
		$leave->save();
		storeCustomField($this->form,$leave->id, $data);
        storeUpload('leave',$leave->id,$request);

		if(config('config.leave_approval_level') == 'designation')
			$leave_status_insert[] = array('leave_id' => $leave->id,'designation_id' => config('config.leave_approval_level_designation'),'status' => 'pending');
		else {
			$parents = getParent(\Auth::user()->Profile->designation_id);

			if(!count($parents))
				$leave_status_insert[] = array('leave_id' => $leave->id,'designation_id' => \Auth::user()->Profile->designation_id,'status' => 'pending');
			else {
				if(config('config.leave_approval_level') == 'single')
					$leave_status_insert[] = array('leave_id' => $leave->id,'designation_id' => $parents[0],'status' => 'pending');
				elseif(config('config.leave_approval_level') == 'multiple'){
					$i = 1;
					foreach($parents as $parent){
						if($i <= config('config.leave_no_of_level') && $parent != null)
						$leave_status_insert[] = array('leave_id' => $leave->id,'designation_id' => $parent,'status' => (($i == 1) ? 'pending' : null));
						$i++;
					}
				}
				elseif(config('config.leave_approval_level') == 'last'){
					$i = 1;
					foreach($parents as $parent){
						$leave_status_insert[] = array('leave_id' => $leave->id,'designation_id' => $parent,'status' => (($i == 1) ? 'pending' : null));
						$i++;
					}
				}
			}
		}
		\App\LeaveStatusDetail::insert($leave_status_insert);
		$leave_status_detail = $leave->LeaveStatusDetail->first();
		if($leave_status_detail){
			$notification_users = implode(',',getUserFromDesignation($leave_status_detail->designation_id));
			$this->sendNotification(['module' => 'leave','module_id' => $leave->id,'url' => '/leave/'.$leave->uuid,'user' => $notification_users,'action' => 'request-leave']);
		}

		$this->logActivity(['module' => 'leave','module_id' => $leave->id,'activity' => 'requested']);

        return response()->json(['message' => trans('messages.leave').' '.trans('messages.requested'), 'status' => 'success']);
	}

	public function update(LeaveRequest $request, Leave $leave){
		if(!Entrust::can('edit-leave') || !$this->isEditable($leave))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);
	
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        if($leave->status != 'pending')
        	return response()->json(['message' => trans('messages.leave_already_processed'),'status' => 'error']);

        $user_leave = \App\UserLeave::whereUserId($leave->user_id)->where('from_date','<=',$request->input('from_date'))->where('to_date','>=',$request->input('to_date'))->first();

        if(!$user_leave)
            return response()->json(['message' => trans('messages.leave_not_defined'), 'status' => 'error']);

        $leave_request_duration = dateDiff($request->input('from_date'),$request->input('to_date'));
        $user_leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$request->input('leave_type_id'))->first();

        $leave_assigned = ($user_leave_detail) ? $user_leave_detail->leave_assigned : 0;
        $leave_used = ($user_leave_detail) ? $user_leave_detail->leave_used : 0;
        $leave_balance = $leave_assigned - $leave_used;

        if($leave_balance < $leave_request_duration)
            return response()->json(['message' => trans('messages.low_leave_balance',['balance' => $leave_balance,'name' => $user_leave_detail->LeaveType->name,'date1' => showDate($user_leave->from_date),'date2' => showDate($user_leave->to_date)]), 'status' => 'error']);

        $other_leaves = Leave::where('id','!=',$leave->id)->whereUserId(\Auth::user()->id)->get();
        $all_leave_days = array();
        foreach($other_leaves as $other_leave){
        	$leave_days = getDateInArray($other_leave->from_date,$other_leave->to_date);
        	foreach($leave_days as $leave_day)
        		$all_leave_days[] = $leave_day;
        	unset($leave_days);
        }

        $leave_collapse = array_intersect($all_leave_days, getDateInArray($request->input('from_date'),$request->input('to_date')));

		if(count($leave_collapse))
            return response()->json(['message' => trans('messages.leave_already_requested_for_some_duration'), 'status' => 'error']);

        $upload_validation = updateUpload('leave',$leave->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$leave->fill($data);
		$leave->save();
		updateCustomField($this->form,$leave->id, $data);

		$this->logActivity(['module' => 'leave','module_id' => $leave->id,'activity' => 'updated']);
        return response()->json(['message' => trans('messages.leave').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Leave $leave, Request $request){
		if(!Entrust::can('delete-leave') || !$this->isEditable($leave))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		if($leave->status != 'pending')
            return response()->json(['message' => trans('messages.leave_already_processed'), 'status' => 'error']);

		deleteUpload('leave',$leave->id);

		$this->logActivity(['module' => 'leave','module_id' => $leave->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $leave->id);
		$leave->delete();
        return response()->json(['message' => trans('messages.leave').' '.trans('messages.deleted'), 'status' => 'success']);
	}

	public function balanceReport(){
		$data = array(
	        		trans('messages.user'),
	        		trans('messages.designation'),
	        		trans('messages.location')
        		);
		$leave_types = \App\LeaveType::all();

		foreach($leave_types as $leave_type)
			array_push($data, $leave_type->name);

		$table_data['leave-balance-report-table'] = array(
				'source' => 'leave-balance-report',
				'title' => trans('messages.leave').' '.trans('messages.balance'),
				'id' => 'leave_balance_report_table',
				'data' => $data,
				'form' => 'leave-balance-report-filter-form'
			);

        $designations = childDesignation();
        $locations = childLocation();

		$assets = ['datatable','graph'];
		$menu = 'leave';
		$current_report = 'leave-balance-report';
		return view('leave.balance_report',compact('table_data','assets','menu','leave_types','designations','locations','current_report'));
	}

	public function balanceReportLists(Request $request){

        $query = getAccessibleUser(\Auth::user()->id,1);

        if($request->has('designation_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('designation_id',$request->input('designation_id'));
            });

        if($request->has('location_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('location_id',$request->input('location_id'));
            });

        $users = $query->get();

        $row = array();

        foreach($users as $user){
        	$user_leave = getUserLeave(date('Y-m-d'),$user->id);

        	$row = array(
        			$user->full_name,
        			$user->designation_name,
        			$user->location_name
        		);

			$leave_types = \App\LeaveType::all();
			foreach($leave_types as $leave_type){
				if($user_leave){
					$user_leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first();

					array_push($row, ($user_leave_detail) ? ($user_leave_detail->leave_used.' / '.$user_leave_detail->leave_assigned) : '-/-' );
				} else 
				array_push($row, '-');
			}

			$rows[] = $row;
        }

        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function dateWiseReport(){
		$data = array(
	        		trans('messages.date'),
	        		trans('messages.leave').' '.trans('messages.detail')
        		);

		$table_data['date-wise-leave-report-table'] = array(
				'source' => 'date-wise-leave-report',
				'title' => trans('messages.leave').' '.trans('messages.report'),
				'id' => 'date_wise_leave_report_table',
				'data' => $data,
				'form' => 'date-wise-leave-report-filter-form'
			);

        $designations = childDesignation();
        $locations = childLocation();

		$assets = ['datatable','graph'];
		$menu = 'leave';
		$current_report = 'date-wise-leave-report';
		return view('leave.date_wise_report',compact('table_data','assets','menu','designations','locations','current_report'));
	}

	public function dateWiseReportLists(Request $request){
		
        $query = getAccessibleUser(\Auth::user()->id,1);

        if($request->has('designation_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('designation_id',$request->input('designation_id'));
            });

        if($request->has('location_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('location_id',$request->input('location_id'));
            });

        $users = $query->get();

        $from_date = ($request->input('from_date')) ? : date('Y-m-d');
        $to_date = ($request->input('to_date')) ? : date('Y-m-d');

        $date = $from_date;

        while($date <= $to_date){
        	$leaves = Leave::whereStatus('approved')->whereRaw('FIND_IN_SET(?,date_approved)', [$date])->get();

        	if($leaves->count()){
	        	$leave_detail = '<ol>';
	        	foreach($leaves as $leave){
	        		$leave_detail .= '<li>';
	        			$leave_detail .= $leave->User->name_with_designation_and_department.' ('.$leave->LeaveType->name.')';
	        		$leave_detail .= '</li>';
	        	}
	        	$leave_detail .= '</ol>';
        	} else 
        	$leave_detail = '-';

        	$rows[] = array(
        			$date,
        			$leave_detail
        		);

        	$date = date('Y-m-d',strtotime($date.' +1 days'));
        }

        $list['aaData'] = $rows;
        return json_encode($list);
	}
}