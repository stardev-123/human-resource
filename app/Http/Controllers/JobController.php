<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use Entrust;
use App\Job;
use Validator;

Class JobController extends Controller{
    use BasicController;

	protected $form = 'job-form';

	public function index(){
		$data = array(
	        		trans('messages.option'),
	        		trans('messages.title'),
	        		trans('messages.no_of').' '.trans('messages.post'),
	        		trans('messages.date_of').' '.trans('messages.closing'),
	        		trans('messages.location'),
	        		trans('messages.designation'),
	        		trans('messages.no_of').' '.trans('messages.application'),
	        		trans('messages.user').' '.trans('messages.w_added'),
	        		trans('messages.created_at')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['job-table'] = array(
				'source' => 'job',
				'title' => 'Job List',
				'id' => 'job_table',
				'data' => $data,
				'form' => 'job-filter-form'
			);

		$assets = ['datatable','redactor'];
		$menu = 'job';

		$accessible_users = getAccessibleUserList();
		$contract_types = \App\ContractType::all()->pluck('name','id')->all();
		$currencies = \App\Currency::all()->pluck('name','id')->all();
		$designations = childDesignation();
		$locations = \App\Location::all()->pluck('name','id')->all();

		return view('job.index',compact('table_data','assets','menu','contract_types','designations','locations','currencies','accessible_users'));
	}

	public function lists(Request $request){

		$query = Job::whereNotNull('id');

		if($request->has('title'))
			$query->where('title','like','%'.$request->input('title').'%');

		if($request->has('gender'))
			$query->whereRaw('FIND_IN_SET(?,gender)', [$request->input('gender')]);

        if($request->has('contract_type_id'))
            $query->whereIn('contract_type_id',$request->input('contract_type_id'));

        if($request->has('designation_id'))
            $query->whereIn('designation_id',$request->input('designation_id'));

        if($request->has('location_id'))
            $query->whereIn('location_id',$request->input('location_id'));

        if($request->has('user_id'))
            $query->whereIn('user_id',$request->input('user_id'));

        if($request->has('date_of_closing_start') && $request->has('date_of_closing_end'))
        	$query->whereBetween('date_of_closing',[$request->input('date_of_closing_start'),$request->input('date_of_closing_end')]);

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

        $jobs = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($jobs as $job){

			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/job/'.$job->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a>'.
				delete_form(['job.destroy',$job->id]).
				'</div>',
				$job->title.' '.(($job->date_of_closing < date('Y-m-d')) ? '<span class="label label-danger">'.trans('messages.w_closed').'</span>' : ''),
				$job->no_of_post,
				showDate($job->date_of_closing),
				$job->location_name,
				$job->designation_name,
				$job->JobApplication->count(),
				$job->UserAdded->name_with_designation_and_department,
				showDateTime($job->created_at),
				);
			$id = $job->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function jobs(){
		$jobs = Job::where('date_of_closing','>=',date('Y-m-d'))->wherePublishPortal(1)->orderBy('date_of_closing','asc')->get();

		if(!$jobs->count())
			return redirect('/login')->withErrors(trans('messages.no_job_openings'));
		
		return view('job.list',compact('jobs'));
	}

	public function detail($slug,$id){
		$job = Job::whereUuid($id)->where('date_of_closing','>=',date('Y-m-d'))->wherePublishPortal(1)->orderBy('date_of_closing','asc')->first();
		if(!$job)
			return redirect('/jobs');

		$jobs = Job::where('date_of_closing','>=',date('Y-m-d'))->wherePublishPortal(1)->orderBy('date_of_closing','asc')->get()->pluck('title','id')->all();

		$job_uploads = \App\Upload::whereModule('job')->whereModuleId($job->id)->whereStatus(1)->get();
		$job_detail = (\Auth::check()) ? 1 : null;
		return view('job.detail',compact('job','job_uploads','jobs','job_detail'));
	}

	public function show(Job $job){

	}

	public function edit(Job $job){
		$contract_types = \App\ContractType::all()->pluck('name','id')->all();
		$currencies = \App\Currency::all()->pluck('name','id')->all();
		$designations = childDesignation();
		$locations = \App\Location::all()->pluck('name','id')->all();
		$uploads = editUpload('job',$job->id);
        return view('job.edit',compact('job','contract_types','currencies','designations','locations','uploads'));
	}

	public function store(JobRequest $request, Job $job){
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('job',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$data['gender'] = implode(',',$request->input('gender'));
	    $job->fill($data);

	    $job->start_age = ($request->input('age_info')) ? $request->input('start_age') : 0;
	    $job->end_age = ($request->input('age_info')) ? $request->input('end_age') : 0;
	    $job->start_salary = ($request->input('salary_info')) ? $request->input('start_salary') : 0;
	    $job->end_salary = ($request->input('salary_info')) ? $request->input('end_salary') : 0;
	    $job->currency_id = ($request->input('salary_info')) ? $request->input('currency_id') : null;
	    $job->uuid = getUuid();
	    $job->description = clean($request->input('description'),'custom');
	    $job->experience = clean($request->input('experience'),'custom');
	    $job->qualification = clean($request->input('qualification'),'custom');
	    $job->user_id = \Auth::user()->id;
		$job->save();
		$this->logActivity(['module' => 'job','module_id' => $job->id,'activity' => 'added']);
		storeCustomField($this->form,$job->id, $data);
        storeUpload('job',$job->id,$request);

        return response()->json(['message' => trans('messages.job').' '.trans('messages.posted'), 'status' => 'success']);
	}

	public function update(JobRequest $request, Job $job){
		
        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        
		$validation_rules['title'] = 'required|unique_with:jobs,designation_id,date_of_closing,'.$job->id;

        $validation = Validator::make($request->all(),$validation_rules);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = updateUpload('job',$job->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$data['gender'] = implode(',',$request->input('gender'));
		$job->fill($data);

	    $job->start_age = ($request->input('age_info')) ? $request->input('start_age') : 0;
	    $job->end_age = ($request->input('age_info')) ? $request->input('end_age') : 0;
	    $job->start_salary = ($request->input('salary_info')) ? $request->input('start_salary') : 0;
	    $job->end_salary = ($request->input('salary_info')) ? $request->input('end_salary') : 0;
	    $job->currency_id = ($request->input('salary_info')) ? $request->input('currency_id') : null;

	    $job->description = clean($request->input('description'),'custom');
	    $job->experience = clean($request->input('experience'),'custom');
	    $job->qualification = clean($request->input('qualification'),'custom');
		$job->save();
		updateCustomField($this->form,$job->id, $data);

		$this->logActivity(['module' => 'job','module_id' => $job->id,'activity' => 'updated']);
		
        return response()->json(['message' => trans('messages.job').' '.trans('messages.updated'), 'status' => 'success']);
	}

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('job')->whereStatus(1)->first();

        $return_uri = (\Auth::check()) ? '/job' : '/jobs';

        if(!$upload)
            return redirect($return_uri)->withErrors(trans('messages.invalid_link'));

        $job = Job::find($upload->module_id);

        if(!$job)
            return redirect($return_uri)->withErrors(trans('messages.invalid_link'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect($return_uri)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

	public function destroy(Request $request, Job $job){
		deleteUpload('job',$job->id);

		$this->logActivity(['module' => 'job','module_id' => $job->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $job->id);
		$job->delete();
        return response()->json(['message' => trans('messages.job').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}