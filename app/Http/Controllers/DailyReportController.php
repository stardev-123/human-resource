<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\DailyReportRequest;
use Entrust;
use App\DailyReport;

Class DailyReportController extends Controller{
    use BasicController;

	protected $form = 'daily-report-form';

	public function isAccessible($daily_report){
		if(in_array($daily_report->user_id, getAccessibleUserId(\Auth::user()->id,1)))
			return 1;
		else
			return 0;
	}

	public function index(DailyReport $daily_report){

		if(!Entrust::can('list-daily-report'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.user'),
	        		trans('messages.date'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['daily-report-table'] = array(
				'source' => 'daily-report',
				'title' => trans('messages.daily').' '.trans('messages.report').' '.trans('messages.list'),
				'id' => 'daily_report_table',
				'data' => $data,
				'form' => 'daily-report-filter-form'
			);

		$accessible_users = getAccessibleUserList();

		$assets = ['datatable','summernote'];
		$menu = 'daily_report';
		return view('daily_report.index',compact('table_data','assets','menu','accessible_users'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-daily-report'))
			return;

		$query = DailyReport::whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1));

		if($request->input('user_id'))
			$query->whereIn('user_id',$request->input('user_id'));

        if($request->has('date_start') && $request->has('date_end'))
        	$query->whereBetween('date',[$request->input('date_start'),$request->input('date_end')]);

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

        $daily_reports = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($daily_reports as $daily_report){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/daily-report/'.$daily_report->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-daily-report') && !$daily_report->is_locked) ? '<a href="#" data-href="/daily-report/'.$daily_report->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((($daily_report->user_id != \Auth::user()->id || !count(getParent(\Auth::user()->id))) && $daily_report->is_locked) ? 
				'<a href="#" data-ajax="1" data-extra="&id='.$daily_report->id.'" data-source="/daily-report/toggle-lock" class="click-alert-message btn btn-sm btn-default"><i class="fa fa-unlock" data-toggle="tooltip" title="'.trans('messages.unlock').'"></i></a>' : '').
				((($daily_report->user_id != \Auth::user()->id || !count(getParent(\Auth::user()->id))) && !$daily_report->is_locked) ? 
				'<a href="#" data-ajax="1" data-extra="&id='.$daily_report->id.'" data-source="/daily-report/toggle-lock" class="click-alert-message btn btn-sm btn-default"><i class="fa fa-lock" data-toggle="tooltip" title="'.trans('messages.lock').'"></i></a>' : '').
				((Entrust::can('delete-daily-report') && !$daily_report->is_locked) ? delete_form(['daily-report.destroy',$daily_report->id]) : '').
				'</div>',
				$daily_report->User->name_with_designation_and_department.' '.(($daily_report->is_locked) ? '<i class="fa fa-lock"></i>' : ''),
				showDate($daily_report->date),
				showDateTime($daily_report->created_at)
				);
			$id = $daily_report->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show(DailyReport $daily_report){

		if(!$this->isAccessible($daily_report))
			return view('global.error',['message' => trans('messages.permission_denied')]);

		$this->updateNotification(['module' => 'daily-report','module_id' => $daily_report->id]);
		$uploads = editUpload('daily-report',$daily_report->id);
		return view('daily_report.show',compact('daily_report','uploads'));
	}

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('daily-report')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/daily-report')->withErrors(trans('messages.invalid_link'));

        $daily_report = DailyReport::find($upload->module_id);

        if(!$daily_report)
            return redirect('/daily-report')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($daily_report))
            return redirect('/daily-report')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/daily-report/'.$daily_report->id)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

	public function create(){
	}

	public function toggleLock(Request $request){
		$daily_report = DailyReport::find($request->input('id'));

		if(!$daily_report)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        if(!$this->isAccessible($daily_report) || ($daily_report->user_id == \Auth::user()->id && count(getParent(\Auth::user()->id))))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $daily_report->is_locked = ($daily_report->is_locked) ? 0 : 1;
        $daily_report->save();

        return response()->json(['message' => '', 'status' => 'success']);
	}

	public function edit(DailyReport $daily_report){

		if(!Entrust::can('edit-daily-report') || !$this->isAccessible($daily_report) || $daily_report->is_locked)
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$accessible_users = getAccessibleUserList();
		$custom_field_values = getCustomFieldValues($this->form,$daily_report->id);
		$uploads = editUpload('daily-report',$daily_report->id);
		return view('daily_report.edit',compact('daily_report','custom_field_values','accessible_users','uploads'));
	}

	public function store(DailyReportRequest $request, DailyReport $daily_report){	

		if(!Entrust::can('create-daily-report'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('daily-report',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$daily_report->fill($data);
		$daily_report->user_id = \Auth::user()->id;
	    $daily_report->description = clean($request->input('description'),'custom');
		$daily_report->save();
		storeCustomField($this->form,$daily_report->id, $data);
        storeUpload('daily-report',$daily_report->id,$request);

        $notification_users = implode(',',getDirectParentUserId());
        $this->sendNotification(['module' => 'daily-report','module_id' => $daily_report->id,'url' => '/daily-report','user' => $notification_users]);

		$this->logActivity(['module' => 'daily_report','module_id' => $daily_report->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.report').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(DailyReportRequest $request, DailyReport $daily_report){

		if(!Entrust::can('edit-daily-report') || !$this->isAccessible($daily_report) || $daily_report->is_locked)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = updateUpload('daily-report',$daily_report->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$daily_report->fill($data);
	    $daily_report->description = clean($request->input('description'),'custom');
		$daily_report->save();

		$this->logActivity(['module' => 'daily_report','module_id' => $daily_report->id,'activity' => 'updated']);

		updateCustomField($this->form,$daily_report->id, $data);
		
        return response()->json(['message' => trans('messages.report').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(DailyReport $daily_report,Request $request){
		if(!Entrust::can('delete-daily-report') || !$this->isAccessible($daily_report) || $daily_report->is_locked)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$this->logActivity(['module' => 'daily_report','module_id' => $daily_report->id,'activity' => 'deleted']);

		deleteUpload('daily-report',$daily_report->id);

		deleteCustomField($this->form, $daily_report->id);
        
        $daily_report->delete();
        
        return response()->json(['message' => trans('messages.report').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>