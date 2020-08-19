<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\PayrollRequest;
use Entrust;
use App\Payroll;
use Validator;
use App\Jobs\GeneratePayroll;

Class PayrollController extends Controller{
    use BasicController;

	protected $form = 'payroll-form';

	public function index(){
		$data = array(
	        		trans('messages.option'),
	        		trans('messages.slip'),
	        		trans('messages.name'),
	        		trans('messages.date'),
	        		trans('messages.duration')
        		);

		array_push($data,trans('messages.hourly').' '.trans('messages.total'));
		array_push($data,trans('messages.overtime').' '.trans('messages.pay'));
		array_push($data,trans('messages.late').' '.trans('messages.deduction'));
		array_push($data,trans('messages.early_leaving').' '.trans('messages.deduction'));

		$salary_heads = \App\SalaryHead::all();
		foreach($salary_heads as $salary_head)
		  array_push($data,$salary_head->name);

		array_push($data,trans('messages.total'));
		$data = putCustomHeads($this->form, $data);

		$table_data['payroll-table'] = array(
				'source' => 'payroll',
				'title' => trans('messages.payroll').' '.trans('messages.list'),
				'id' => 'payroll_table',
				'data' => $data,
				'form' => 'payroll-filter-form'
			);

		$assets = ['datatable','graph'];
		$menu = 'payroll';
		return view('payroll.index',compact('table_data','assets','menu'));
	}

	public function lists(Request $request){

		$payrolls = Payroll::whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->get();

		$salary_heads = \App\SalaryHead::all();
        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();
        $sum_total = 0;

        foreach($payrolls as $payroll){
		    $amount = array();
		    $sum_amount = array();
		    $total = 0;

		    foreach($salary_heads as $salary_head){
		      $amount[$salary_head->id] = 0;
		      $sum_amount[$salary_head->id] = 0;
		    }

		    foreach($payroll->PayrollDetail as $payroll_detail){
		      $amount[$payroll_detail->salary_head_id] = round($payroll_detail->amount,2);
		      $sum_amount[$payroll_detail->salary_head_id] += round($payroll_detail->amount,2);
		    }

		    foreach($salary_heads as $salary_head){
		      if($salary_head->type == "earning")
		        $total += $amount[$salary_head->id];
		      else
		        $total -= $amount[$salary_head->id];
		    }

		    $total += $payroll->hourly;
		    $total += $payroll->overtime;
		    $total -= $payroll->late;
		    $total -= $payroll->early_leaving;

		    $row = array(
		        '<div class="btn-group btn-group-xs">'.
		          '<a href="/payroll/'.$payroll->uuid.'" class="btn btn-default btn-xs" data-toggle="tooltip" title="'.trans('messages.view').'"> <i class="fa fa-arrow-circle-o-right"></i></a>'.
		        (Entrust::can('delete-payroll') ? delete_form(['payroll.destroy',$payroll->id]) : '').'</div>',
		        $payroll->id,
		        $payroll->User->name_with_designation_and_department,
		        date('d M Y',strtotime($payroll->created_at)),
		        showDate($payroll->from_date).' '.trans('messages.to').' '.showDate($payroll->to_date),
		        );

		    array_push($row,currency($payroll->hourly,0,$payroll->currency_id));
		    array_push($row,currency($payroll->overtime,0,$payroll->currency_id));
		    array_push($row,currency($payroll->late,0,$payroll->currency_id));
		    array_push($row,currency($payroll->early_leaving,0,$payroll->currency_id));

		    foreach($amount as $value)
		      array_push($row,currency($value,0,$payroll->currency_id));

		    array_push($row,currency($total,0,$payroll->currency_id));

		    $id = $payroll->id;

		    $sum_total += $total;
		    unset($amount);

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');

		    $rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function show($id){

		$payroll = Payroll::whereUuid($id)->first();

		if(!$payroll)
			return redirect('/payroll')->withErrors(trans('messages.invalid_link'));

		$user = $payroll->User;

		if(!in_array($user->id,getAccessibleUserId(\Auth::user()->id,1)))
			return redirect('/payroll')->withErrors(trans('messages.invalid_link'));

    	$payroll_details = $payroll->PayrollDetail->pluck('amount','salary_head_id')->all();

        $user_salary = getUserSalary($payroll->from_date,$user->id);

    	$earning_salary_heads = \App\SalaryHead::where('type','=','earning')->get();
   	 	$deduction_salary_heads = \App\SalaryHead::where('type','=','deduction')->get();
	    $salaries = $user_salary->UserSalaryDetail;

		$data = $this->getAttendanceSummary($user,$payroll->from_date,$payroll->to_date);
		$summary = $data['summary'];
		$att_summary = $data['att_summary'];
		$total_earning = 0;
		$total_deduction = 0;
		$this->updateNotification(['module' => 'payroll','module_id' => $payroll->id]);

		return view('payroll.show',compact('payroll','payroll_details','user','earning_salary_heads','deduction_salary_heads','summary','att_summary','salaries','user_salary','total_earning','total_deduction'));
	}

	public function generate($id,$action = 'print'){

		$payroll = Payroll::whereUuid($id)->first();

		if(!$payroll)
			return redirect('/payroll')->withErrors(trans('messages.invalid_link'));

		$user = $payroll->User;

		if(!in_array($user->id,getAccessibleUserId(\Auth::user()->id,1)))
			return redirect('/payroll')->withErrors(trans('messages.invalid_link'));

    	$payroll_details = $payroll->PayrollDetail->pluck('amount','salary_head_id')->all();
        $user_salary = getUserSalary($payroll->from_date,$user->id);

        $leave_types = \App\LeaveType::all();
    	$earning_salary_heads = \App\SalaryHead::where('type','=','earning')->get();
   	 	$deduction_salary_heads = \App\SalaryHead::where('type','=','deduction')->get();
		$summary_data = $this->getAttendanceSummary($user,$payroll->from_date,$payroll->to_date);
		$summary = $summary_data['summary'];
		$att_summary = $summary_data['att_summary'];

      	$user_leave = \App\UserLeave::whereUserId($payroll->user_id)->where('from_date','<=',$payroll->from_date)->where('to_date','>=',$payroll->from_date)->first();
		$user_leave_data = array();

      	if($user_leave){
	      	foreach($leave_types as $leave_type){
	      		$leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first();
	      		$leave_used = ($leave_detail) ? $leave_detail->leave_used : 0;
	      		$leave_assigned = ($leave_detail) ? $leave_detail->leave_assigned : 0;
	      		$user_leave_data[$leave_type->id] = array(
	      			'leave_used' => $leave_used,
	      			'leave_assigned' => $leave_assigned
	      		);
	      	}
      	}

   	 	$data = [
   	 		'user' => $user,
	 		'payroll' => $payroll,
   	 		'earning_salary_heads' => $earning_salary_heads,
   	 		'deduction_salary_heads' => $deduction_salary_heads,
   	 		'payroll_details' => $payroll_details,
   	 		'total_earning' => 0,
   	 		'total_deduction' => 0,
   	 		'summary' => $summary,
   	 		'att_summary' => $att_summary,
   	 		'leave_types' => $leave_types,
   	 		'user_salary' => $user_salary,
   	 		'company_address' => $this->getCompanyAddress(),
   	 		'user_leave_data' => $user_leave_data
   	 	];

   	 	if($action == 'pdf'){
   	 		$pdf = \PDF::loadView('payroll.print', $data);
			return $pdf->download($payroll->User->fullname.'.pdf');
   	 	}

   	 	if($action == 'mail'){
   	 		if(!getMode())
				return redirect('/payroll/'.$payroll->uuid)->withSuccess(trans('messages.email').' '.trans('messages.sent'));

			$mail_data = $this->templateContent(['slug' => 'payroll','payroll' => $payroll,'user' => $user]);
			if(count($mail_data)){
				$pdf = \PDF::loadView('payroll.print',$data);
		   	 	$mail['email'] = $user->email;
		   	 	$mail['subject'] = $mail_data['subject'];
		   	 	$mail['filename'] = $payroll->User->fullname.'.pdf';
		   	 	$body = $mail_data['body'];

		   	 	\Mail::send('emails.email', compact('body'), function ($message) use($mail,$pdf) {
		   	 		$message->attachData($pdf->output(), $mail['filename']);
		   	 		$message->to($mail['email'])->subject($mail['subject']);
		   	 	});
		   	 	$this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body,'module' => 'payroll','module_id' =>$payroll->id));
			}
			return redirect('/payroll/'.$payroll->uuid)->withSuccess(trans('messages.email').' '.trans('messages.sent'));
   	 	}

    	return view('payroll.print',$data);
	}

	public function create(Request $request){
		if(!Entrust::can('create-payroll'))
			return redirect('/dashboard')->withErrors(trans('messages.permission_denied'));

		$from_date = $request->input('from_date') ? : '';
		$to_date = $request->input('to_date') ? : '';
		$user_id = $request->input('user_id') ? : '';

		$users = getAccessibleUserList();

	    $menu = 'payroll';
	    if(!$request->input('submit'))
	    	return view('payroll.create',compact('from_date','to_date','user_id','users','menu'));

		$validation = Validator::make($request->all(),[
		'user_id' => 'required',
		'from_date' => 'required|date|before_or_equal:to_date',
		'to_date' => 'required|date',
		]);

		if($validation->fails())
		  return redirect()->back()->withInput()->withErrors($validation->messages());

		$count = Payroll::whereUserId($user_id)->
		where(function ($query) use($from_date,$to_date) { $query->where(function ($query) use($from_date,$to_date){
		  $query->where('from_date','>=',$from_date)
		  ->where('from_date','<=',$to_date);
		})->orWhere(function ($query)  use($from_date,$to_date) {
		  $query->where('to_date','>=',$from_date)
		    ->where('to_date','<=',$to_date);
		});})->count();

		if($count)
			return redirect()->back()->withInput()->withErrors(trans('messages.payroll_already_generated'));

	    $user = \App\User::find($user_id);

        $user_shift = getShift($from_date,$user->id);

        if(!$user_shift)
			return redirect()->back()->withInput()->withErrors(trans('messages.shift_not_defined'));

	    $user_salary = getUserSalary($from_date,$user->id);
		if(!$user_salary || ($user_salary && $user_salary->to_date != null && $user_salary->to_date < $to_date))
			return redirect()->back()->withInput()->withErrors(trans('messages.salary_not_defined'));

		$data = $this->getAttendanceSummary($user,$from_date,$to_date);
		$total = $data['total'];
		$summary = $data['summary'];
		$att_summary = $data['att_summary'];
		$working_days = $att_summary['P'] + $att_summary['L'] + $att_summary['H'];
		$half_days = $att_summary['HD'];

	  	$no_of_days = dateDiff($to_date,$from_date);
	    $salary_fraction = ($no_of_days) ? ($att_summary['W'] / $no_of_days) : 0;

	    $earning_salary_heads = \App\SalaryHead::where('type','=','earning')->get();
	    $deduction_salary_heads = \App\SalaryHead::where('type','=','deduction')->get();
	    $salaries = $user_salary->UserSalaryDetail;

		$from_date_month = date('m',strtotime($from_date));
		$to_date_month = date('m',strtotime($to_date));
		$from_date_year = date('Y',strtotime($from_date));
		$to_date_year = date('Y',strtotime($to_date));

		if($from_date_month != $to_date_month){
			$payroll_days = (config('config.payroll_days') == 'start_date') ? cal_days_in_month(CAL_GREGORIAN, $from_date_month, $from_date_year) : cal_days_in_month(CAL_GREGORIAN, $to_date_month, $to_date_year);
		} else
			$payroll_days = cal_days_in_month(CAL_GREGORIAN, $from_date_month, $from_date_year);

		$salary_values = array();

		foreach($earning_salary_heads as $earning_salary_head)
			$salary_values[$earning_salary_head->id] = 0;
		foreach($deduction_salary_heads as $deduction_salary_head)
			$salary_values[$deduction_salary_head->id] = 0;

		foreach($salaries as $salary){
			$salary_amount = (($salary->amount/$payroll_days)*$working_days) + ((($salary->amount/$payroll_days)*$half_days)/2);
			$salary_values[$salary->salary_head_id] = ($user_salary->type == 'hourly') ? 0 : (($salary->SalaryHead->is_fixed) ? currency($salary->amount,0,$user_salary->currency_id) : currency($salary_amount,0,$user_salary->currency_id) );
		}

		$hourly_payroll = ($user_salary->type == 'hourly') ? 1 : 0;
		$hourly = currency((floor($total['total_working'] / 3600) * $user_salary->hourly_rate),0,$user_salary->currency_id);
		$late = (!$hourly_payroll) ? currency((floor($total['total_late'] / 3600) * $user_salary->late_hourly_rate),0,$user_salary->currency_id) : 0;
		$overtime = (!$hourly_payroll) ? currency((floor($total['total_overtime'] / 3600) * $user_salary->overtime_hourly_rate),0,$user_salary->currency_id) : 0;
		$early_leaving = (!$hourly_payroll) ? currency((floor($total['total_early_leaving'] / 3600) * $user_salary->early_leaving_hourly_rate),0,$user_salary->currency_id) : 0;

		return view('payroll.create',compact('users','user','user_id','earning_salary_heads','deduction_salary_heads','salaries','summary','att_summary','salary_fraction','menu','from_date','to_date','salary_values','hourly','late','overtime','early_leaving','hourly_payroll','user_salary'));
	}

	public function calculateTotalSalary($payroll,$salary_heads,$symbol = 0){
		if($payroll->is_hourly)
			return ($symbol) ? currency($payroll->hourly,1,$payroll->currency_id) : currency($payroll->hourly,0,$payroll->currency_id);

		$total_salary = 0;
		foreach($salary_heads as $salary_head){
			if($salary_head == 'earning'){
				$payroll_salary = $payroll->PayrollDetail->where('salary_head_id',$salary_head->id)->first();
				$total_salary += ($payroll_salary) ? $payroll_salary->amount : 0;
			} elseif($salary_head == 'deduction'){
				$payroll_salary = $payroll->PayrollDetail->where('salary_head_id',$salary_head->id)->first();
				$total_salary -= ($payroll_salary) ? $payroll_salary->amount : 0;
			}
		}
		$total_salary += $payroll->overtime;
		$total_salary -= $payroll->late;
		$total_salary -= $payroll->early_leaving;

		return ($symbol) ? currency($total_salary,1,$payroll->currency_id) : currency($total_salary,0,$payroll->currency_id);
	}

	public function store(PayrollRequest $request){
		if(!Entrust::can('create-payroll'))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		$input_error = 0;
		foreach($request->input('salary_head') as $salary_head_input){
			if(!is_numeric($salary_head_input) || $salary_head_input < 0)
				$input_error++;
		}

		if($input_error)
			return response()->json(['message' => trans('messages.salary_input_numeric'),'status' => 'error']);

		if(!in_array($request->input('user_id'),getAccessibleUserId()))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$count = Payroll::whereUserId($request->input('user_id'))->
		where(function ($query) use($request) { $query->where(function ($query) use($request){
		  $query->where('from_date','>=',$request->input('from_date'))
		  ->where('from_date','<=',$request->input('to_date'));
		})->orWhere(function ($query)  use($request) {
		  $query->where('to_date','>=',$request->input('from_date'))
		    ->where('to_date','<=',$request->input('to_date'));
		});})->count();

		if($count)
            return response()->json(['message' => trans('messages.payroll_already_generated'), 'status' => 'error']);

	    $user = \App\User::find($request->input('user_id'));

	    $user_salary = getUserSalary($request->input('from_date'),$user->id);

		if(!$user_salary || ($user_salary && $user_salary->to_date != null && $user_salary->to_date < $request->input('to_date')))
			return response()->json(['message' => trans('messages.salary_not_defined'),'status' => 'error']);

		$salary_heads = \App\SalaryHead::all();

		$payroll = Payroll::firstOrCreate([
			'user_id' => $request->input('user_id'),
			'from_date' => $request->input('from_date'),
			'to_date' => $request->input('to_date')
		]);

		$payroll->uuid = getUuid();
		$payroll->currency_id = $user_salary->currency_id;
		$payroll->user_id = $request->input('user_id');
		$payroll->from_date = $request->input('from_date');
		$payroll->to_date = $request->input('to_date');
		$payroll->date_of_payroll = $request->input('date_of_payroll');
		$payroll->is_hourly = ($request->input('type') == 'hourly') ? 1 : 0;
		$payroll->hourly = ($request->input('type') == 'hourly') ? $request->input('hourly') : 0;
		$payroll->late = ($request->input('type') != 'hourly') ? $request->input('late') : 0;
		$payroll->overtime = ($request->input('type') != 'hourly') ? $request->input('overtime') : 0;
		$payroll->early_leaving = ($request->input('type') != 'hourly') ? $request->input('early_leaving') : 0;

		$payroll->save();

		$salary_head_value = $request->input('salary_head');

		if($request->input('type') != 'hourly')
		foreach($salary_heads as $salary_head){
			$payroll_detail = \App\PayrollDetail::firstOrCreate(array(
				'payroll_id' => $payroll->id,
				'salary_head_id' => $salary_head->id
				));
			$payroll_detail->payroll_id = $payroll->id;
			$payroll_detail->salary_head_id = $salary_head->id;
			$payroll_detail->amount = ($request->input('type') != 'hourly') ? $salary_head_value[$salary_head->id] : 0;
			$payroll_detail->save();
		}
		$data = $request->all();
		storeCustomField($this->form,$payroll->id, $data);
        $this->sendNotification(['module' => 'payroll','module_id' => $payroll->id,'url' => '/payroll/'.$payroll->uuid,'user' => $payroll->user_id,'action' => 'create-payroll']);

	    $this->logActivity(['module' => 'payroll','unique_id' => $payroll->id,'activity' => 'generated']);
	  	return response()->json(['message' => trans('messages.payroll').' '.trans('messages.generated'), 'status' => 'success','redirect' => '/payroll/'.$payroll->uuid]);
	}

	public function edit($id){
		$payroll = Payroll::find($id);

		if(!$payroll || !Entrust::can('edit-payroll'))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$user = $payroll->User;

		if(!in_array($user->id,getAccessibleUserId()))
            return view('global.error',['message' => trans('messages.permission_denied')]);

	    $user_salary = getUserSalary($payroll->from_date,$user->id);
        $payroll_details = $payroll->PayrollDetail->pluck('amount','salary_head_id')->all();

	    $earning_salary_heads = \App\SalaryHead::where('type','=','earning')->get();
	    $deduction_salary_heads = \App\SalaryHead::where('type','=','deduction')->get();
		$custom_field_values = getCustomFieldValues($this->form,$payroll->id);

		$salary_values = array();

		foreach($earning_salary_heads as $earning_salary_head)
			$salary_values[$earning_salary_head->id] = 0;
		foreach($deduction_salary_heads as $deduction_salary_head)
			$salary_values[$deduction_salary_head->id] = 0;

		foreach($payroll_details as $key => $payroll_detail){
			$salary_values[$key] = ($payroll->is_hourly) ? 0 : (currency($payroll_detail,0,$payroll->currency_id));
		}

		$hourly_payroll = $payroll->is_hourly;
		$hourly = currency($payroll->hourly,0,$payroll->currency_id);
		$late = (!$hourly_payroll) ? currency($payroll->late,0,$payroll->currency_id) : 0;
		$overtime = (!$hourly_payroll) ? currency($payroll->overtime,0,$payroll->currency_id) : 0;
		$early_leaving = (!$hourly_payroll) ? currency($payroll->early_leaving,0,$payroll->currency_id) : 0;

        return view('payroll.edit',compact('payroll','earning_salary_heads','deduction_salary_heads','custom_field_values','salary_values','hourly','late','overtime','hourly_payroll','early_leaving','user_salary'));
	}

	public function update(Request $request, $id){
		$payroll = Payroll::find($id);

		if(!$payroll || !Entrust::can('edit-payroll'))
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$input_error = 0;
		foreach($request->input('salary_head') as $salary_head_input){
			if(!is_numeric($salary_head_input) || $salary_head_input < 0)
				$input_error++;
		}

		if($input_error)
			return response()->json(['message' => trans('messages.salary_input_numeric'),'status' => 'error']);

		$user = $payroll->User;

		if(!in_array($user->id,getAccessibleUserId()))
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
        	return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$salary_heads = \App\SalaryHead::all();

		$old_total_salary = $this->calculateTotalSalary($payroll,$salary_heads);
		$old_date_of_payroll = $payroll->date_of_payroll;

		$payroll = Payroll::firstOrNew(['id' => $id]);
		$payroll->date_of_payroll = $request->input('date_of_payroll');
		$payroll->is_hourly = ($request->input('type') == 'hourly') ? 1 : 0;
		$payroll->hourly = ($request->input('type') == 'hourly') ? $request->input('hourly') : 0;
		$payroll->late = ($request->input('type') != 'hourly') ? $request->input('late') : 0;
		$payroll->overtime = ($request->input('type') != 'hourly') ? $request->input('overtime') : 0;
		$payroll->early_leaving = ($request->input('type') != 'hourly') ? $request->input('early_leaving') : 0;
		$payroll->save();

		$salary_head_value = $request->input('salary_head');

		if($request->input('type') != 'hourly') {
			foreach($salary_heads as $salary_head){
				$payroll_detail = \App\PayrollDetail::firstOrCreate([
					'payroll_id' => $payroll->id,
					'salary_head_id' => $salary_head->id
				]);
				$payroll_detail->payroll_id = $payroll->id;
				$payroll_detail->salary_head_id = $salary_head->id;
				$payroll_detail->amount = ($request->input('type') != 'hourly') ? $salary_head_value[$salary_head->id] : 0;
				$payroll_detail->save();
			}
		} else
			\App\PayrollDetail::wherePayrollId($payroll->id)->delete();

		$data = $request->all();
		updateCustomField($this->form,$payroll->id, $data);

		$total_salary = $this->calculateTotalSalary($payroll,$salary_heads);

		if($total_salary != $old_total_salary || $payroll->date_of_payroll != $old_date_of_payroll)
        	$this->sendNotification(['module' => 'payroll','module_id' => $payroll->id,'url' => '/payroll/'.$payroll->uuid,'user' => $payroll->user_id,'action' => 'update-payroll']);

	    $this->logActivity(['module' => 'payroll','unique_id' => $payroll->id,'activity' => 'updated']);

	  	return response()->json(['message' => trans('messages.payroll').' '.trans('messages.updated'), 'status' => 'success','redirect' => '/payroll/'.$payroll->uuid]);
	}

	public function createMultiple(){
		if(!Entrust::can('create-multiple-payroll'))
			return redirect('/payroll')->withErrors(trans('messages.permission_denied'));

		return view('payroll.multiple');
	}

	public function postCreateMultiple(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

		if(!Entrust::can('create-multiple-payroll'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$validation = Validator::make($request->all(),[
			'from_date' => 'required|date|before_or_equal:to_date',
			'to_date' => 'required|date'
		]);

		if($validation->fails())
			return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$from_date = $request->input('from_date');
		$to_date = $request->input('to_date');
		$send_mail = ($request->has('send_mail')) ? 1 : 0;

		$this->dispatch(new GeneratePayroll($from_date,$to_date,$send_mail));

	    return response()->json(['message' => trans('messages.request').' '.trans('messages.submitted'), 'status' => 'success','redirect' => '/payroll']);
	}

	public function destroy($id,Request $request){
	    $payroll = Payroll::find($id);

	    if(!Entrust::can('delete-payroll') || !$payroll)
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		$user = $payroll->User;

		if(!in_array($user->id,getAccessibleUserId()))
	        return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

	    $this->logActivity(['module' => 'payroll','unique_id' => $payroll->id,'activity' => 'deleted']);
		deleteCustomField($this->form, $payroll->id);
	    $payroll->delete();

        return response()->json(['message' => trans('messages.payroll').' '.trans('messages.deleted'), 'status' => 'success']);
	}

	public function monthlyReportGraph(Request $request){
        $currencies = \App\Currency::all();
        $total_salary = array();
        foreach($currencies as $currency)
            $currency_legend[] = $currency->detail;

        $payrolls = Payroll::all();

        for($i=0;$i<12;$i++){
            $month_year = date('Y-m', strtotime(date('Y-m-d').' - '.$i.' months'));
            $month_year_name = date('M-Y',strtotime($month_year.'-01'));
            $first_date = date('Y-m-d',strtotime($month_year.'-01'));
            $last_date = date('Y-m-t',strtotime($month_year.'-01'));

            $filter_payroll = $payrolls->filter(function ($item) use ($first_date,$last_date) {
                return (data_get($item, 'date_of_payroll') >= $first_date) && (data_get($item, 'date_of_payroll') < $last_date);
            })->all();

            foreach($currencies as $currency)
                $total_salary[$currency->id] = 0;
            foreach($filter_payroll as $payroll){

            	$salary = 0;

            	if($payroll->is_hourly)
            		$salary += $payroll->hourly;
            	else{
            		$salary -= $payroll->late;
            		$salary -= $payroll->early_leaving;
            		$salary += $payroll->overtime;
            		foreach($payroll->PayrollDetail as $payroll_detail){
            			if($payroll_detail->SalaryHead->type == 'earning')
            				$salary += $payroll_detail->amount;
            			else
            				$salary -= $payroll_detail->amount;
            		}
            	}
            	$salary = currency($salary,0);

            	$total_salary[$payroll->currency_id] += $salary;
            }

            $y_data[] = $month_year_name;
            foreach($total_salary as $key => $value)
                $total_net_salary[$key][] = $value;
        }

        $net_salary_data = array();
        foreach($currencies as $currency){
            $net_salary_data[] = array(
                'name' => $currency->detail,
                'type' => 'bar',
                'data' => $total_net_salary[$currency->id]
                );
        }
        $extra_height = 100;
        $height = 25*count($currency_legend)*count($y_data) + $extra_height;
        $graph_data = array(
            'payroll' => array(
                'y_data' => $y_data,
                'height' => $height,
                'text' => toWordTranslate('monthly-payroll-statistics'),
                'legend' => $currency_legend,
                'x_data' => $net_salary_data
            )
        );

        return json_encode($graph_data);
	}
}
