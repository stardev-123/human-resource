<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\JobApplicationRequest;
use Entrust;
use App\JobApplication;
use Validator;

Class JobApplicationController extends Controller{
    use BasicController;

	protected $form = 'job-application-form';
    protected $portal = 'portal';

    public function index(){
        $data = array(
                    trans('messages.option'),
                    trans('messages.job').' '.trans('messages.title'),
                    trans('messages.name'),
                    trans('messages.email'),
                    trans('messages.gender'),
                    trans('messages.date_of').' '.trans('messages.application'),
                    trans('messages.status')
                );

        $data = putCustomHeads($this->form, $data);

        $table_data['job-application-table'] = array(
                'source' => 'job-application',
                'title' => trans('messages.job').' '.trans('messages.application').' '.trans('messages.list'),
                'id' => 'job_application_table',
                'data' => $data,
                'form' => 'job-application-filter-form'
            );

        $assets = ['datatable'];
        $menu = 'job,job_application';

        $jobs = \App\Job::where('date_of_closing','>=',date('Y-m-d'))->wherePublishPortal(1)->orderBy('date_of_closing','asc')->get()->pluck('title','id')->all();

        return view('job_application.index',compact('table_data','assets','menu','jobs'));
    }

    public function lists(Request $request){

        $query = JobApplication::whereNotNull('id');

        if($request->has('email'))
            $query->where('email','like','%'.$request->input('email').'%');

        if($request->has('status'))
            $query->whereIn('status',$request->input('status'));

        if($request->has('gender'))
            $query->whereIn('gender',$request->input('gender'));

        if($request->has('job_id'))
            $query->whereIn('job_id',$request->input('job_id'));

        if($request->has('source'))
            $query->whereIn('source',$request->input('source'));

        if($request->has('date_of_birth_start') && $request->has('date_of_birth_end'))
            $query->whereBetween('date_of_birth',[$request->input('date_of_birth_start'),$request->input('date_of_birth_end')]);

        if($request->has('date_of_application_start') && $request->has('date_of_application_end'))
            $query->whereBetween('date_of_application',[$request->input('date_of_application_start'),$request->input('date_of_application_end')]);

        $job_applications = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($job_applications as $job_application){

            $row = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="/job-application/'.$job_application->id.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a>'.
                (($job_application->source != $this->portal) ? '<a href="#" data-href="/job-application/'.$job_application->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a>' : '').
                (($job_application->source != $this->portal) ? delete_form(['job-application.destroy',$job_application->id]) : '').
                '</div>',
                $job_application->Job->title);

                if($job_application->applicant_user_id){
                    array_push($row, $job_application->ApplicantUser->full_name.' <span class="label label-danger">'.trans('messages.user').'</span>');
                    array_push($row, $job_application->ApplicantUser->email);
                    array_push($row, trans('messages.'.$job_application->ApplicantUser->Profile->gender));
                } else {
                    array_push($row, $job_application->full_name);
                    array_push($row, $job_application->email);
                    array_push($row, trans('messages.'.$job_application->gender));
                }
                
                array_push($row,showDate($job_application->date_of_application));
                array_push($row,jobApplicationStatusLable($job_application->status));
            $id = $job_application->id;

            foreach($col_ids as $col_id)
                array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
            $rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function show(JobApplication $job_application){
        $job_application_status = translateList('job_application_status');
        return view('job_application.show',compact('job_application','job_application_status'));
    }

    public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('job-application')->whereStatus(1)->first();

        $return_uri = '/job-application';

        if(!$upload)
            return redirect($return_uri)->withErrors(trans('messages.invalid_link'));

        $job_application = JobApplication::find($upload->module_id);

        if(!$job_application)
            return redirect($return_uri)->withErrors(trans('messages.invalid_link'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect($return_uri)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function edit(JobApplication $job_application){

        if($job_application->source == $this->portal)
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $jobs = \App\Job::where('date_of_closing','>=',date('Y-m-d'))->wherePublishPortal(1)->orderBy('date_of_closing','asc')->get()->pluck('title','id')->all();

        $uploads = editUpload('job-application',$job_application->id);

        return view('job_application.edit',compact('job_application','uploads','jobs'));
    }

    public function detail(Request $request){
        $job_application = JobApplication::find($request->input('id'));
        if(!$job_application)
            return;

        $uploads = \App\Upload::whereModule('job-application')->whereModuleId($job_application->id)->whereStatus(1)->get();

        return view('job_application.detail',compact('job_application','uploads'))->render();
    }

    public function updateStatus($id, Request $request){

        $validation = Validator::make($request->all(),['status' => 'required','remarks' => 'required']);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $job_application = JobApplication::find($id);

        if(!$job_application)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        if($request->input('status') == $job_application->status)
            return response()->json(['message' => trans('messages.choose_different_job_application_status'),'status' => 'error']);

        $job_application_status_detail = new \App\JobApplicationStatusDetail;
        $job_application_status_detail->job_application_id = $job_application->id;
        $job_application_status_detail->status = $request->input('status');
        $job_application_status_detail->remarks = $request->input('remarks');
        $job_application_status_detail->user_id = \Auth::user()->id;
        $job_application_status_detail->save();

        $job_application->status = $request->input('status');
        $job_application->save();

        return response()->json(['status' => 'success']);
    }

    public function destroyStatus($id, Request $request){
        $job_application_status_detail = \App\JobApplicationStatusDetail::find($id);

        if($job_application_status_detail->user_id != \Auth::user()->id)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        $job_application = JobApplication::find($job_application_status_detail->job_application_id);

        if(!$job_application)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        $status = \App\JobApplicationStatusDetail::whereJobApplicationId($job_application->id)->orderBy('created_at','desc')->first();

        if($status->id != $id)
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $job_application_status_detail->delete();

        $new_status = \App\JobApplicationStatusDetail::whereJobApplicationId($job_application->id)->orderBy('created_at','desc')->first();

        $job_application->status = ($new_status) ? $new_status->status : 'applied';
        $job_application->save();
        
        $this->logActivity(['module' => 'job-application','module_id' => $job_application->id,'activity' => 'updated']);

        return response()->json(['status' => 'success']);
    }

    public function listStatus(Request $request){

        $job_application = JobApplication::find($request->input('id'));

        if(!$job_application)
            return;

        $job_application_status_details = \App\JobApplicationStatusDetail::whereJobApplicationId($request->input('id'))->orderBy('created_at','desc')->get();

        return view('job_application.list_status',compact('job_application_status_details','job_application'))->render();
    }

	public function store(JobApplicationRequest $request, JobApplication $job_application){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('job-application',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        if($request->has('first_name'))
            $already_applied = JobApplication::whereJobId($request->input('job_id'))->whereEmail($request->input('email'))->count();
        else
            $already_applied = JobApplication::whereJobId($request->input('job_id'))->whereApplicantUserId(\Auth::user()->id)->count();

        if($already_applied)
            return response()->json(['message' => trans('messages.job_application_already_posted'),'status' => 'error']);

        $job = \App\Job::find($request->input('job_id'));

        if($job->age_info){
            $date_of_birth = $request->has('date_of_birth') ? $request->input('date_of_birth') : (\Auth::user()->Profile->date_of_birth) ? : null;
            $age = $date_of_birth ? (date('Y') - date('Y',strtotime($date_of_birth))) : 0;
            if($age < $job->start_age || $age > $job->end_age)
                return response()->json(['message' => trans('messages.age_not_eligible_for_job_post'),'status' => 'error']);
        }

		$data = $request->all();
	    $job_application->fill($data);
	    $job_application->applicant_user_id = ($request->has('first_name')) ? null : \Auth::user()->id;
	    $job_application->user_id = (\Auth::check()) ? \Auth::user()->id : null;
        $job_application->status = 'applied';
        $job_application->date_of_application = ($request->input('date_of_application')) ? : date('Y-m-d');
        $job_application->source = ($request->has('source')) ? $request->input('source') : $this->portal;
        $job_application->save();
		storeCustomField($this->form,$job_application->id, $data);
        storeUpload('job-application',$job_application->id,$request);
        
        if(\Auth::check())
            $this->logActivity(['module' => 'job-application','module_id' => $job_application->id,'activity' => 'submitted']);

        return response()->json(['message' => trans('messages.job').' '.trans('messages.application').' '.trans('messages.submitted'), 'status' => 'success']);
	}

    public function update(JobApplicationRequest $request, JobApplication $job_application){

        $validation = validateCustomField($this->form,$request);
        
        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        if($request->has('first_name'))
            $already_applied = JobApplication::whereJobId($request->input('job_id'))->whereEmail($request->input('email'))->where('id','!=',$job_application->id)->count();
        else
            $already_applied = JobApplication::whereJobId($request->input('job_id'))->whereApplicantUserId(\Auth::user()->id)->where('id','!=',$job_application->id)->count();

        if($already_applied)
            return response()->json(['message' => trans('messages.job_application_already_posted'),'status' => 'error']);

        $job = \App\Job::find($request->input('job_id'));

        if($job->age_info){
            $date_of_birth = $request->has('date_of_birth') ? $request->input('date_of_birth') : (\Auth::user()->Profile->date_of_birth) ? : null;
            $age = $date_of_birth ? (date('Y') - date('Y',strtotime($date_of_birth))) : 0;
            if($age < $job->start_age || $age > $job->end_age)
                return response()->json(['message' => trans('messages.age_not_eligible_for_job_post'),'status' => 'error']);
        }

        $upload_validation = updateUpload('job-application',$job_application->id,$request);

        if($upload_validation['status'] == 'error')
            return response()->json($upload_validation);

        $data = $request->all();
        $job_application->fill($data);
        $job_application->date_of_application = $request->input('date_of_application');
        $job_application->source = $request->input('source');
        $job_application->save();
        updateCustomField($this->form,$job_application->id, $data);
        
        $this->logActivity(['module' => 'job-application','module_id' => $job_application->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.job').' '.trans('messages.application').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, JobApplication $job_application){
        deleteUpload('job-application',$job_application->id);

        $this->logActivity(['module' => 'job-application','module_id' => $job_application->id,'activity' => 'deleted']);

        deleteCustomField($this->form, $job_application->id);
        $job_application->delete();
        return response()->json(['message' => trans('messages.application').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}