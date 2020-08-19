<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;
use Entrust;
use App\Expense;

Class ExpenseController extends Controller{
    use BasicController;

	protected $form = 'expense-form';

	public function isAccessible($expense){
		if($expense->user_id == \Auth::user()->id)
			return 1;
		elseif(in_array($expense->user_id,getAccessibleUserId()))
			return 1;
		elseif(in_array(\Auth::user()->Profile->designation_id,\App\ExpenseStatusDetail::whereExpenseId($expense->id)->get()->pluck('designation_id')->all()))
			return 1;
		else
			return 0;
	}

	public function status($expense){
		if($expense->status == 'pending' || $expense->status == null)
			return '<span class="label label-info">'.trans('messages.pending').'</span>';
		elseif($expense->status == 'approved')
			return '<span class="label label-success">'.trans('messages.w_approved').'</span>';
		elseif($expense->status == 'rejected')
			return '<span class="label label-danger">'.trans('messages.w_rejected').'</span>';
	}

	public function isEditable($expense){
		$expense_status_detail = $expense->ExpenseStatusDetail->first();
		if($expense_status_detail && $expense_status_detail->status == 'pending' && $expense->user_id == \Auth::user()->id)
			return 1;
		else
			return 0;
	}

	public function index(Expense $expense){
		if(!Entrust::can('list-expense'))
			return redirect('/home')->withErrors(trans('messages.permission_denied'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.user'),
	        		trans('messages.expense').' '.trans('messages.head'),
	        		trans('messages.date_of').' '.trans('messages.expense'),
	        		trans('messages.amount'),
	        		trans('messages.status')
        		);

		$data = putCustomHeads($this->form, $data);

		$table_data['expense-table'] = array(
				'source' => 'expense',
				'title' => trans('messages.expense').' '.trans('messages.list'),
				'id' => 'expense_table',
				'data' => $data,
				'form' => 'expense-filter-form'
			);

		$expense_heads = \App\ExpenseHead::all()->pluck('name','id')->all();
		$currencies = \App\Currency::all()->pluck('name','id')->all();
		$accessible_users = getAccessibleUserList(\Auth::user()->id,1);

		$assets = ['datatable','graph'];
		$menu = 'expense';
		return view('expense.index',compact('table_data','assets','menu','expense_heads','currencies','accessible_users'));
	}

	public function lists(Request $request){
		if(!Entrust::can('list-expense'))
			return;

		$expense_status_details = \App\ExpenseStatusDetail::whereDesignationId(\Auth::user()->Profile->designation_id)->get()->pluck('expense_id')->all();

		$accessible_user_expenses = Expense::whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->get()->pluck('id')->all();

		$all_expenses = array_unique(array_merge($expense_status_details,$accessible_user_expenses));
		$query = Expense::whereIn('id',$all_expenses);

		if($request->has('user_id'))
			$query->whereIn('user_id',$request->input('user_id'));

		if($request->has('status'))
			$query->whereIn('status',$request->input('status'));

		if($request->has('expense_head_id'))
			$query->whereIn('expense_head_id',$request->input('expense_head_id'));

        if($request->has('date_of_expense_start') && $request->has('date_of_expense_end'))
        	$query->whereBetween('date_of_expense',[$request->input('date_of_expense_start'),$request->input('date_of_expense_end')]);

        if($request->has('created_at_start') && $request->has('created_at_end'))
        	$query->whereBetween('created_at',[$request->input('created_at_start').' 00:00:00',$request->input('created_at_end').' 23:59:59']);

		$expenses = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($expenses as $expense){
			$row = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="/expense/'.$expense->uuid.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
				((Entrust::can('edit-expense') && $this->isEditable($expense)) ? '<a href="#" data-href="/expense/'.$expense->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
				((Entrust::can('delete-expense') && $this->isEditable($expense) && $expense->status != 'pending') ? delete_form(['expense.destroy',$expense->id]) : '').
				'</div>',
				$expense->User->name_with_designation_and_department,
				$expense->ExpenseHead->name,
				showDate($expense->date_of_expense),
				currency($expense->amount,1,$expense->Currency->id),
				$this->status($expense)
				);
			$id = $expense->id;

			foreach($col_ids as $col_id)
				array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
			$rows[] = $row;
        }
        $list['aaData'] = $rows;

        $statuses = array();
        $heads = array();
        $departments = array();
        $locations = array();
        foreach($expenses as $expense){
            if($expense->status)
                $statuses[] = \Lang::has('messages.w_'.$expense->status) ? trans('messages.w_'.$expense->status) : toWordTranslate($expense->status);
            if($expense->expense_head_id)
                $heads[] = $expense->ExpenseHead->name;
            if($expense->User->department_name)
                $departments[] = $expense->User->department_name;
            if($expense->User->location_name)
                $locations[] = $expense->User->location_name;
        }

        $list['graph']['expense_status'] = getPieCharData($statuses,'expense-status-wise-graph');
        $list['graph']['expense_head'] = getPieCharData($heads,'expense-head-wise-graph');
        $list['graph']['expense_department'] = getPieCharData($departments,'department-wise-expense-graph');
        $list['graph']['expense_location'] = getPieCharData($locations,'location-wise-expense-graph');

        return json_encode($list);
	}

	public function edit(Expense $expense){
		if(!Entrust::can('edit-expense') || !$this->isEditable($expense))
            return view('global.error',['message' => trans('messages.permission_denied')]);

		$expense_heads = \App\ExpenseHead::all()->pluck('name','id')->all();
		$currencies = \App\Currency::all()->pluck('name','id')->all();

		$custom_field_values = getCustomFieldValues($this->form,$expense->id);
		$uploads = editUpload('expense',$expense->id);

        return view('expense.edit',compact('expense','expense_heads','currencies','uploads','custom_field_values'));
	}

	public function expenseStatusDetail(Request $request){
		$expense = Expense::find($request->input('id'));

		if(!$this->isAccessible($expense))
			return;

		return view('expense.status_detail',compact('expense'));
	}

	public function show($uuid){
		$expense = Expense::whereUuid($uuid)->first();

		if(!$expense || !$this->isAccessible($expense))
			return redirect('/expense')->withErrors(trans('messages.permission_denied'));

		$menu = 'expense';

        $expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();
        $expense_status_enabled = $this->getExpenseStatus($expense);
		$this->updateNotification(['module' => 'expense','module_id' => $expense->id]);

		return view('expense.show',compact('expense','menu','expense_status_detail','expense_status_enabled'));
	}

	public function detail(Request $request){
		$expense = Expense::find($request->input('id'));

		if(!$expense || !$this->isAccessible($expense))
			return redirect('/expense')->withErrors(trans('messages.permission_denied'));

		$uploads = \App\Upload::whereModule('expense')->whereModuleId($expense->id)->whereStatus(1)->get();

		$status = $this->status($expense);

		return view('expense.detail',compact('expense','uploads','status'))->render();
	}

	public function getExpenseStatus($expense){

        $expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();

        if($expense_status_detail){
        	$previous_expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->where('id','<',$expense_status_detail->id)->orderBy('id','desc')->first();
        	$next_expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->where('id','>',$expense_status_detail->id)->first();

	        $previous_expense_status = ($previous_expense_status_detail) ? $previous_expense_status_detail->status : null;
	        $next_expense_status = ($next_expense_status_detail) ? $next_expense_status_detail->status : null;
        }

		$last_expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->orderBy('id','desc')->first();

		if(!$expense_status_detail)
			$expense_status_enabled = 0;
		elseif($previous_expense_status == 'rejected' || $previous_expense_status == 'pending')
			$expense_status_enabled = 0;
		elseif($last_expense_status_detail && $last_expense_status_detail->designation_id == \Auth::user()->Profile->designation_id)
			$expense_status_enabled = 1;
		elseif($next_expense_status == 'pending' || $next_expense_status == null)
			$expense_status_enabled = 1;
		elseif($expense_status_detail == 'pending' || $expense_status_detail == null)
			$expense_status_enabled = 1;
		else
			$expense_status_enabled = 0;

		return $expense_status_enabled;
	}

	public function updateStatus($id, Request $request){
		$expense = Expense::find($id);

		if(!$expense)
			return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

		$expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->whereDesignationId(\Auth::user()->Profile->designation_id)->first();

		$previous_expense_status = $expense_status_detail->status;

		if(!$this->isAccessible($expense) && !$expense_status_detail)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $expense_status_enabled = $this->getExpenseStatus($expense);

        if($expense_status_enabled == 0)
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

		$expense_status_detail->status = $request->input('status');
		$expense_status_detail->remarks = $request->input('remarks');
		$expense_status_detail->save();

        $next_expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->where('id','>',$expense_status_detail->id)->first();
        if($next_expense_status_detail){
        	$next_expense_status_detail->status = ($request->input('status') == 'pending') ? null : 'pending';
        	$next_expense_status_detail->save();
        }
		$last_expense_status_detail = \App\ExpenseStatusDetail::whereExpenseId($expense->id)->orderBy('id','desc')->first();

        if($request->input('status') == 'rejected'){
        	$expense->status = 'rejected';
        	$expense->save();
        	\App\ExpenseStatusDetail::where('id','>',$expense_status_detail->id)->update(['status' => null]);
        } else {
        	$expense->status = 'pending';
        	$expense->save();
        }

		if($last_expense_status_detail && $last_expense_status_detail->designation_id == \Auth::user()->Profile->designation_id){
			$expense->status = ($request->input('status')) ? : 'pending';
			$expense->save();
		}

		if($previous_expense_status != $expense_status_detail->status){
			if($expense_status_detail->status == 'rejected'){
				$this->sendNotification(['module' => 'expense','module_id' => $expense->id,'url' => '/expense/'.$expense->uuid,'user' => $expense->user_id,'action' => 'reject-expense']);
			} elseif($expense_status_detail->status == 'approved'){
				if($expense->status != 'approved' && $next_expense_status_detail){
					$notification_users = implode(',',getUserFromDesignation($next_expense_status_detail->designation_id));
					$this->sendNotification(['module' => 'expense','module_id' => $expense->id,'url' => '/expense/'.$expense->uuid,'user' => $expense->user_id,'action' => 'partially-approve-expense']);
					$this->sendNotification(['module' => 'expense','module_id' => $expense->id,'url' => '/expense/'.$expense->uuid,'user' => $notification_users,'action' => 'create-expense']);
				} elseif($expense->status == 'approved'){
					$this->sendNotification(['module' => 'expense','module_id' => $expense->id,'url' => '/expense/'.$expense->uuid,'user' => $expense->user_id,'action' => 'approve-expense']);
				}
			}
		}

		$this->logActivity(['module' => 'expense','module_id' => $expense->id,'activity' => 'status_updated']);

        return response()->json(['message' => trans('messages.expense').' '.trans('messages.status').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function download($id){
        $upload = \App\Upload::whereUuid($id)->whereModule('expense')->whereStatus(1)->first();

        if(!$upload)
            return redirect('/expense')->withErrors(trans('messages.invalid_link'));

        $expense = Expense::find($upload->module_id);

        if(!$expense)
            return redirect('/expense')->withErrors(trans('messages.invalid_link'));

        if(!$this->isAccessible($expense))
            return redirect('/expense')->withErrors(trans('messages.permission_denied'));

        if(!\Storage::exists('attachments/'.$upload->attachments))
            return redirect('/expense/'.$expense->uuid)->withErrors(trans('messages.file_not_found'));

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
	}

	public function store(ExpenseRequest $request, Expense $expense){
		if(!Entrust::can('create-expense'))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $upload_validation = validateUpload('expense',$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

        if(config('config.expense_approval_level') == 'designation' && !\App\Designation::whereId(config('config.expense_approval_level_designation'))->count())
        	return response()->json(['message' => trans('messages.invalid_expense_approver'),'status' => 'error']);

		$data = $request->all();
	    $expense->fill($data);
	    $expense->uuid = getUuid();
	    $expense->status = 'pending';
		$expense->user_id = \Auth::user()->id;
		$expense->save();
		storeCustomField($this->form,$expense->id, $data);
        storeUpload('expense',$expense->id,$request);

		if(config('config.expense_approval_level') == 'designation')
			$expense_status_insert[] = array('expense_id' => $expense->id,'designation_id' => config('config.expense_approval_level_designation'),'status' => 'pending');
		else {
			$parents = getParent(\Auth::user()->Profile->designation_id);

			if(!count($parents))
				$expense_status_insert[] = array('expense_id' => $expense->id,'designation_id' => \Auth::user()->Profile->designation_id,'status' => 'pending');
			else {
				if(config('config.expense_approval_level') == 'single')
					$expense_status_insert[] = array('expense_id' => $expense->id,'designation_id' => $parents[0],'status' => 'pending');
				elseif(config('config.expense_approval_level') == 'multiple'){
					$i = 1;
					foreach($parents as $parent){
						if($i <= config('config.expense_no_of_level') && $parent != null)
						$expense_status_insert[] = array('expense_id' => $expense->id,'designation_id' => $parent,'status' => (($i == 1) ? 'pending' : null));
						$i++;
					}
				}
				elseif(config('config.expense_approval_level') == 'last'){
					$i = 1;
					foreach($parents as $parent){
						$expense_status_insert[] = array('expense_id' => $expense->id,'designation_id' => $parent,'status' => (($i == 1) ? 'pending' : null));
						$i++;
					}
				}
			}
		}
		\App\ExpenseStatusDetail::insert($expense_status_insert);
		$expense_status_detail = $expense->ExpenseStatusDetail->first();
		if($expense_status_detail){
			$notification_users = implode(',',getUserFromDesignation($expense_status_detail->designation_id));
			$this->sendNotification(['module' => 'expense','module_id' => $expense->id,'url' => '/expense/'.$expense->uuid,'user' => $notification_users,'action' => 'create-expense']);
		}

		$this->logActivity(['module' => 'expense','module_id' => $expense->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.expense').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function update(ExpenseRequest $request, Expense $expense){

		if(!Entrust::can('edit-expense') || !$this->isEditable($expense))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $validation = validateCustomField($this->form,$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        if($expense->status != 'pending')
        	return response()->json(['message' => trans('messages.expense_already_processed'),'status' => 'error']);

        $upload_validation = updateUpload('expense',$expense->id,$request);

        if($upload_validation['status'] == 'error')
        	return response()->json($upload_validation);

		$data = $request->all();
		$expense->fill($data);
		$expense->save();
		updateCustomField($this->form,$expense->id, $data);

		$this->logActivity(['module' => 'expense','module_id' => $expense->id,'activity' => 'updated']);
        return response()->json(['message' => trans('messages.expense').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Expense $expense, Request $request){
		if(!Entrust::can('delete-expense') || !$this->isEditable($expense))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

		deleteUpload('expense',$expense->id);

		$this->logActivity(['module' => 'expense','module_id' => $expense->id,'activity' => 'deleted']);

		deleteCustomField($this->form, $expense->id);
		$expense->delete();
        return response()->json(['message' => trans('messages.expense').' '.trans('messages.deleted'), 'status' => 'success']);
	}

	public function monthlyReportGraph(Request $request){
        $currencies = \App\Currency::all();
        $total_expense = array();
        foreach($currencies as $currency)
            $currency_legend[] = $currency->detail;

        $expenses = Expense::all();

        for($i=0;$i<12;$i++){
            $month_year = date('Y-m', strtotime(date('Y-m-d').' - '.$i.' months'));
            $month_year_name = date('M-Y',strtotime($month_year.'-01'));
            $first_date = date('Y-m-d',strtotime($month_year.'-01'));
            $last_date = date('Y-m-t',strtotime($month_year.'-01'));

            $filter_expense = $expenses->filter(function ($item) use ($first_date,$last_date) {
                return (data_get($item, 'date_of_expense') >= $first_date) && (data_get($item, 'date_of_expense') < $last_date);
            })->all();

            foreach($currencies as $currency)
                $total_expense[$currency->id] = 0;
            foreach($filter_expense as $expense){
            	$amount = 0;
				$amount += $expense->amount;
            	$amount = currency($amount,0);
            	$total_expense[$expense->currency_id] += $amount;
            }

            $y_data[] = $month_year_name;
            foreach($total_expense as $key => $value)
                $total_net_expense[$key][] = $value;
        }

        $expense_data = array();
        foreach($currencies as $currency){
            $expense_data[] = array(
                'name' => $currency->detail,
                'type' => 'bar',
                'data' => $total_net_expense[$currency->id]
                );
        }
        $extra_height = 100;
        $height = 25*count($currency_legend)*count($y_data) + $extra_height;
        $graph_data = array(
            'expense' => array(
                'y_data' => $y_data,
                'height' => $height,
                'text' => toWordTranslate('monthly-expense-statistics'),
                'legend' => $currency_legend,
                'x_data' => $expense_data
            )
        );

        return json_encode($graph_data);
	}
}
