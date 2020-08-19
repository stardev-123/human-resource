<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Clock;
use Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

Class ClockController extends Controller{
    use BasicController;

	protected $form = 'clock-form';

	public function lists(Request $request){
        $clocks = \App\Clock::whereUserId(\Auth::user()->id)
            ->where('date','=',date('Y-m-d'))
            ->orderBy('clock_in')
            ->get();

        $attendance = $this->getAttendanceSummary(\Auth::user(),date('Y-m-d'),date('Y-m-d'));

        return view('clock.list',compact('clocks','attendance'))->render();
	}

	public function clockButton(Request $request){
        $clock = \App\Clock::where('user_id','=',\Auth::user()->id)
            ->where('date','=',date('Y-m-d'))
            ->where('clock_out','=',null)
            ->count();

        $clock_status = ($clock) ? 'clock_out' : 'clock_in';

        return view('clock.clock_button',compact('clock_status'))->render();
	}

	public function in(Request $request){
		if(!$request->has('api') && !\Auth::check())
	        return response()->json(['message' => trans('messages.session_expire'), 'status' => 'error']);

		$datetime = ($request->input('datetime')) ? : date('Y-m-d H:i:s');
		$time = date('H:i:s',strtotime($datetime));
		$date = date('Y-m-d',strtotime($datetime));
		$user_id = ($request->input('user_id')) ? : \Auth::user()->id;

       	$user_shift = getShift($date,$user_id);

        if($user_shift->overnight && $time >= '00:00:00' && $time <= $user_shift->out_time){
        	$date = date('Y-m-d',strtotime($date . ' -1 days'));
        	$user_shift = getShift($date,$user_id);
        }

       	$in_date = $date;
        $in_time = $user_shift->in_time;
        $out_date = date('Y-m-d',strtotime($date . (($user_shift->overnight) ? ' +1 days' : '')));
        $out_time = $out_date.' '.$user_shift->out_time;

		$clocks = Clock::where('user_id','=',$user_id)
			->where('date','=',$date)
			->where(function($query) use($datetime) {
				$query->where('clock_out','=',null)
				->orWhere('clock_out','>=',date('Y-m-d H:i:s',strtotime($datetime)));
			})->count();

		if($clocks){
			if($request->has('api'))
				return response()->json(['type' => 'error','error_code' => '105']);
			else
				return response()->json(['message' => trans('messages.invalid_clock_in'), 'status' => 'error']);
		}

		$clock = new Clock;
		$clock->date = $date;
		$clock->clock_in = date('Y-m-d H:i:s',strtotime($datetime));
		$clock->user_id = $user_id;
		$clock->save();

		$this->logActivity(['module' => 'attendance','module_id' => $clock->id,'activity' => 'clocked_in']);

		if($request->has('api'))
			return response()->json(['type'=>'success']);
		else
			return response()->json(['message' => trans('messages.clocked_in'),'status'=>'success']);
	}

	public function out(Request $request){
		if(!$request->has('api') && !\Auth::check())
	        return response()->json(['message' => trans('messages.session_expire'), 'status' => 'error']);

		$datetime = ($request->input('datetime')) ? : date('Y-m-d H:i:s');
		$time = date('H:i:s',strtotime($datetime));
		$date = date('Y-m-d',strtotime($datetime));
		$user_id = ($request->input('user_id')) ? : \Auth::user()->id;

		$next_date = date('Y-m-d',strtotime($date.' +1 days'));

       	$user_shift = getShift($date,$user_id);
       	$user_next_date_shift = getShift($next_date,$user_id);

        if($user_shift->overnight && $time >= '00:00:00' && $datetime < $next_date.' '.$user_next_date_shift->in_time){
        	$date = date('Y-m-d',strtotime($date . ' -1 days'));
        	$user_shift = getShift($date,$user_id);
        }

		$clock = Clock::where('user_id','=',$user_id)
			->where('date','=',$date)
			->where('clock_out','=',null)
			->first();

		if(!$clock){
			if($request->has('api'))
				return response()->json(['type' => 'error','error_code' => '106']);
			else
				return response()->json(['message' => trans('messages.not_clocked_in'), 'status' => 'error']);
		}

		if($clock->clock_in > date('Y-m-d H:i:s',strtotime($datetime))){
			if($request->has('api'))
				return response()->json(['type' => 'error','error_code' => '107']);
			else
				return response()->json(['message' => trans('messages.out_time_not_less_than_in_time'), 'status' => 'error']);
		}

		$clock->clock_out = date('Y-m-d H:i:s',strtotime($datetime));
		$clock->save();

		$this->logActivity(['module' => 'attendance','module_id' => $clock->id,'activity' => 'clocked_out']);

		if($request->has('api'))
			return response()->json(['type'=>'success']);
		else
			return response()->json(['message' => trans('messages.clocked_out'),'status'=>'success']);
	}

	public function dailyShift(){

        $data = array(
        		trans('messages.user'),
        		trans('messages.shift'),
        		trans('messages.in_time'),
        		trans('messages.out_time'),
        		trans('messages.overnight')
        		);

        $table_data['daily-shift-table'] = array(
			'source' => 'daily-shift',
			'title' => trans('messages.shift').' '.trans('messages.list'),
			'id' => 'daily_shift_table',
			'data' => $data,
			'form' => 'daily-shift-filter-form'
		);
		$assets = ['datatable','graph'];
		$menu = 'attendance,shift_report';
		$current_report = 'daily-shift';
        $designations = childDesignation();
        $locations = childLocation();

		return view('clock.daily_shift',compact('table_data','assets','menu','current_report','designations','locations'));
	}

	public function dailyShiftLists(Request $request){
		$date = ($request->input('date')) ? : date('Y-m-d');

		$user_query = getAccessibleUser(\Auth::user()->id,1);

		if($request->input('designation_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('designation_id',$request->input('designation_id'));
			});

		if($request->input('location_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('location_id',$request->input('location_id'));
			});

		$users = $user_query->get();

		$rows = array();
		$shift_names = array();
		$user_shift_names = array();
		foreach(\App\Shift::all() as $shift){
			$user_shift_names[$shift->name] = 0;
			$shift_names[] = $shift->name;
		}
		array_push($shift_names, toWordTranslate('custom-shift'));
		$user_shift_names[toWordTranslate('custom-shift')] = 0;

		foreach($users as $user){
			$user_shift = getShift($date,$user->id);

			$in_time = ($user_shift) ? showTime($date.' '.$user_shift->in_time) : '-';
			$out_time = ($user_shift) ? showTime($date.' '.$user_shift->out_time) : '-';
			$overnight = ($user_shift) ? (($user_shift->overnight) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') : '-';

			if($in_time == $out_time)
				$in_time = $out_time = $overnight = '-';

			$shift_name = ($user_shift) ? (($user_shift->shift_id) ? $user_shift->Shift->name : toWordTranslate('custom-shift')) : '-';

			$rows[] = array(
					$user->name_with_designation_and_department,
					$shift_name,
					$in_time,
					$out_time,
					$overnight,
				);

			if($user_shift)
				$user_shift_names[$shift_name]++;
		}
        $list['aaData'] = $rows;

        $user_shift_name_count = array();
    	foreach($user_shift_names as $key => $value)
    		$user_shift_name_count[] = $value;

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($shift_names) + $extra_height;

        $list['graph'] = [
        	'daily_shift' => [
            	'title_text' => toWordTranslate('shift-report-of').' '.showDate($date),
            	'name' => trans('messages.shift'),
                'ydata' => $shift_names,
                'xdata' => $user_shift_name_count,
                'legend' => [trans('messages.shift')],
                'title' => trans('messages.shift'),
                'height' => $height
            ]
        ];
        return json_encode($list);
	}

	public function dateWiseShift(){

        $data = array(
        		trans('messages.date'),
        		trans('messages.shift'),
        		trans('messages.in_time'),
        		trans('messages.out_time'),
        		trans('messages.overnight')
        		);

        $table_data['date-wise-shift-table'] = array(
			'source' => 'date-wise-shift',
			'title' => trans('messages.shift').' '.trans('messages.list'),
			'id' => 'date_wise_shift_table',
			'data' => $data,
			'form' => 'date-wise-shift-filter-form'
		);
		$assets = ['datatable','graph'];
		$menu = 'attendance,shift_report';
		$current_report = 'date-wise-shift';

		$accessible_users = getAccessibleUserList();

		return view('clock.date_wise_shift',compact('table_data','assets','menu','accessible_users','current_report'));
	}

	public function dateWiseShiftLists(Request $request){
		$from_date = ($request->input('from_date')) ? : date('Y-m-d');
		$to_date = ($request->input('to_date')) ? : date('Y-m-d');
		$user_id = ($request->input('user_id')) ? : \Auth::user()->id;
		$user = \App\User::find($user_id);

		$date = $from_date;
		$rows = array();
		$shift_names = array();
		$user_shift_names = array();
		foreach(\App\Shift::all() as $shift){
			$user_shift_names[$shift->name] = 0;
			$shift_names[] = $shift->name;
		}
		array_push($shift_names, toWordTranslate('custom-shift'));
		$user_shift_names[toWordTranslate('custom-shift')] = 0;

		while($date <= $to_date){
			$user_shift = getShift($date,$user_id);

			$in_time = ($user_shift) ? showTime($date.' '.$user_shift->in_time) : '-';
			$out_time = ($user_shift) ? showTime($date.' '.$user_shift->out_time) : '-';
			$overnight = ($user_shift) ? (($user_shift->overnight) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') : '-';

			if($in_time == $out_time)
				$in_time = $out_time = $overnight = '-';

			$shift_name = ($user_shift->shift_id) ? $user_shift->Shift->name : toWordTranslate('custom-shift');

			$rows[] = array(
					showDate($date),
					$shift_name,
					$in_time,
					$out_time,
					$overnight,
				);

			$user_shift_names[$shift_name]++;
			$date = date('Y-m-d',strtotime($date.' +1 days'));
		}
        $list['aaData'] = $rows;

        $user_shift_name_count = array();
    	foreach($user_shift_names as $key => $value)
    		$user_shift_name_count[] = $value;

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($shift_names) + $extra_height;

        $list['graph'] = [
        	'date_wise_shift' => [
            	'title_text' => toWordTranslate('shift-report-of').' '.$user->name_with_designation_and_department.' '.trans('messages.from').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            	'name' => trans('messages.shift'),
                'ydata' => $shift_names,
                'xdata' => $user_shift_name_count,
                'legend' => [trans('messages.shift')],
                'title' => trans('messages.shift'),
                'height' => $height
            ]
        ];
        return json_encode($list);
	}

	public function dailyAttendance(){
		$data = array(
	        		trans('messages.status'),
	        		trans('messages.user'),
	        		trans('messages.in_time'),
	        		trans('messages.out_time'),
	        		trans('messages.late'),
	        		trans('messages.early_leaving'),
	        		trans('messages.overtime'),
	        		trans('messages.total').' '.trans('messages.work'),
	        		trans('messages.total').' '.trans('messages.rest'),
	        		''
        		);

		$table_data['daily-attendance-table'] = array(
				'source' => 'daily-attendance',
				'title' => trans('messages.daily').' '.trans('messages.attendance').' '.trans('messages.list'),
				'id' => 'daily_attendance_table',
				'data' => $data,
				'form' => 'daily-attendance-form'
			);

        $designations = childDesignation();
        $locations = childLocation();
		$assets = ['datatable','graph'];
		$menu = 'attendance,report';
		$current_report = 'daily-attendance';
		return view('clock.daily_attendance',compact('table_data','assets','menu','current_report','designations','locations'));
	}

	public function dailyAttendanceLists(Request $request){
		$date = ($request->input('date')) ? : date('Y-m-d');

		$user_query = getAccessibleUser(\Auth::user()->id,1);

		if($request->input('designation_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('designation_id',$request->input('designation_id'));
			});

		if($request->input('location_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('location_id',$request->input('location_id'));
			});

		$users = $user_query->get();
        $holiday = \App\Holiday::where('date','=',$date)->count();
        $clocks = Clock::where('date','=',$date)->get();
        $leaves = \App\Leave::whereRaw('FIND_IN_SET(?,date_approved)', [$date])->get();

        $total_late = $total_early_leaving = $total_overtime = $total_working = $total_rest = 0;
        $rows = array();
        $raw_graph_data = array();
        $all_attendance = array();
        $all_tags = array();

        foreach($users as $user){

        	$late = $early_leaving = $overtime = $working = $rest = 0;
        	$tag = '';
        	$user_shift = getShift($date,$user->id);
        	$user_shift->in_time = $date.' '.$user_shift->in_time;
        	$user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

        	$out = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->last();
        	$in = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->first();
			$records = $clocks->where('date',$date)->where('user_id',$user->id)->all();
			$leave = $leaves->where('user_id',$user->id)->count();

			if(isset($in))
				$attendance = 'P';
			elseif($leave && $leave->LeaveType->is_half_day)
				$attendance = 'HD';
			elseif($leave && !$leave->LeaveType->is_half_day)
				$attendance = 'L';
			elseif($holiday)
				$attendance = 'H';
			elseif(!$holiday && $date < date('Y-m-d'))
				$attendance = 'A';
			else
				$attendance = '';

			$late = (isset($in) && (strtotime($in->clock_in) > strtotime($user_shift->in_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->in_time) - strtotime($in->clock_in)) : 0;
			$total_late += $late;
			if($late){
				$all_tags[] = 'Late';
				$tag .= $this->attendanceTag('late');
			}

			$early_leaving = (isset($out) && $out->clock_out != null && (strtotime($out->clock_out) < strtotime($user_shift->out_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->out_time) - strtotime($out->clock_out)) : 0;
			$total_early_leaving += $early_leaving;
			if($early_leaving){
				$all_tags[] = 'Early';
				$tag .= $this->attendanceTag('early_leaving');
			}

			foreach($records as $record){
				if($record->clock_in >= $user_shift->out_time && $record->clock_out != null)
					$overtime += strtotime($record->clock_out) - strtotime($record->clock_in);
				elseif($record->clock_in < $user_shift->out_time && $record->clock_out > $user_shift->out_time)
					$overtime += strtotime($record->clock_out) - strtotime($user_shift->out_time);
			}
			$total_overtime += $overtime;
			if($overtime){
				$all_tags[] = 'Overtime';
				$tag .= $this->attendanceTag('overtime');
			}

			foreach($records as $record)
				$working += ($record->clock_out != null) ? abs(strtotime($record->clock_out) - strtotime($record->clock_in)) : 0;
			$total_working += $working;

			$rest = (isset($in) && $out->clock_out != null) ? (abs(strtotime($out->clock_out) - strtotime($in->clock_in)) - $working) : 0;
			$total_rest += $rest;

			$rows[] = array(
				$this->attendanceLabel($attendance),
				$user->name_with_designation_and_department,
				(isset($in)) ? showTime($in->clock_in) : '-',
				(isset($out)) ? showTime($out->clock_out) : '-',
				showDuration($late),
				showDuration($early_leaving),
				showDuration($overtime),
				showDuration($working),
				showDuration($rest),
				$tag
			);
			unset($tag);

			$raw_graph_data[] = array(
				'name' => $user->full_name,
				'late' => ($late) ? $late/60 : 0,
				'early_leaving' => ($early_leaving) ? $early_leaving/60 : 0,
				'overtime' => ($overtime) ? $overtime/60 : 0,
				'working' => ($working) ? $working/60 : 0,
				'rest' => ($rest) ? $rest/60 : 0,
			);

			if($attendance)
				$all_attendance[] = $attendance;
        }

        $all_attendance = array_count_values($all_attendance);
        $all_tags = array_count_values($all_tags);

        if($request->has('attendance_statistics'))
        	return view('clock.statistics',compact('all_attendance','all_tags'))->render();

        $list['aaData'] = $rows;
        $list['foot'] = '<tr>
			<th colspan="4"></th>
			<th>'.showDuration($total_late).'</th>
			<th>'.showDuration($total_early_leaving).'</th>
			<th>'.showDuration($total_overtime).'</th>
			<th>'.showDuration($total_working).'</th>
			<th>'.showDuration($total_rest).'</th>
			<th></th>
		</tr>';

		$ydata = array();
		$late_xdata = array();
		$early_leaving_xdata = array();
		$overtime_xdata = array();
		$working_xdata = array();
		$rest_xdata = array();
		foreach($raw_graph_data as $data){
			$ydata[] = $data['name'];
			$late_xdata[] = $data['late'];
			$early_leaving_xdata[] = $data['early_leaving'];
			$overtime_xdata[] = $data['overtime'];
			$working_xdata[] = $data['working'];
			$rest_xdata[] = $data['rest'];
		}

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($ydata) + $extra_height;

        $list['graph'] = [
            'daily_attendance' => [
            	'late' => [
            		'title_text' => trans('messages.late').' '.trans('messages.statistics').' '.showDate($date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $late_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.late'),
	                'height' => $height,
            	],
            	'early_leaving' => [
            		'title_text' => trans('messages.early_leaving').' '.trans('messages.statistics').' '.showDate($date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $early_leaving_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.early_leaving'),
	                'height' => $height,
            	],
            	'overtime' => [
            		'title_text' => trans('messages.overtime').' '.trans('messages.statistics').' '.showDate($date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $overtime_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.overtime'),
	                'height' => $height,
            	],
            	'working' => [
            		'title_text' => trans('messages.working').' '.trans('messages.statistics').' '.showDate($date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $working_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.working'),
	                'height' => $height,
            	],
            	'rest' => [
            		'title_text' => trans('messages.rest').' '.trans('messages.statistics').' '.showDate($date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $rest_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.rest'),
	                'height' => $height,
            	]
            ]
        ];

        return json_encode($list);
	}

	public function dateWiseAttendance(){
		$data = array(
	        		trans('messages.status'),
	        		trans('messages.date'),
	        		trans('messages.in_time'),
	        		trans('messages.out_time'),
	        		trans('messages.late'),
	        		trans('messages.early_leaving'),
	        		trans('messages.overtime'),
	        		trans('messages.total').' '.trans('messages.work'),
	        		trans('messages.total').' '.trans('messages.rest'),
	        		''
        		);

		$table_data['date-wise-attendance-table'] = array(
				'source' => 'date-wise-attendance',
				'title' => trans('messages.date').' '.trans('messages.wise').' '.trans('messages.attendance').' '.trans('messages.list'),
				'id' => 'date_wise_attendance_table',
				'data' => $data,
				'form' => 'date-wise-attendance-form'
			);

		$accessible_users = getAccessibleUserList(\Auth::user()->id,1);
		$assets = ['datatable','graph'];
		$menu = 'attendance,report';
		$current_report = 'date-wise-attendance';
		return view('clock.date_wise_attendance',compact('table_data','assets','menu','accessible_users','current_report'));
	}

	public function dateWiseAttendanceLists(Request $request){
		$user_id = $request->input('user_id') ? : \Auth::user()->id;
		$user = \App\User::find($user_id);
		$from_date = $request->input('from_date') ? : date('Y-m-d');
		$to_date = $request->input('to_date') ? : date('Y-m-d');
        $clocks = Clock::whereUserId($user_id)->where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $holidays = \App\Holiday::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $leaves = \App\Leave::whereStatus('approved')->whereUserId($user_id)->get();

		$leave_approved = array();
		$half_day_leave_approved = array();
        foreach($leaves as $leave){
            $leave_date_approved = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
            foreach($leave_date_approved as $date_approved){
            	if($leave->is_half_day)
            		$half_day_leave_approved[] = $date_approved;
            	else
                	$leave_approved[] = $date_approved;
            }
        }

        $total_late = $total_early_leaving = $total_overtime = $total_working = $total_rest = 0;
        $rows = array();

        $date = $from_date;
        while($date <= $to_date){
        	$late = $early_leaving = $overtime = $working = $rest = 0;
        	$tag = '';
        	$user_shift = getShift($date,$user->id);
        	$user_shift->in_time = $date.' '.$user_shift->in_time;
        	$user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

        	$out = $clocks->where('date',$date)->sortBy('clock_in')->last();
        	$in = $clocks->where('date',$date)->sortBy('clock_in')->first();
			$records = $clocks->where('date',$date)->all();

			$late = (isset($in) && (strtotime($in->clock_in) > strtotime($user_shift->in_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->in_time) - strtotime($in->clock_in)) : 0;
			$tag .= ($late) ? $this->attendanceTag('late') : '';
			$total_late += $late;

			$early_leaving = (isset($out) && $out->clock_out != null && (strtotime($out->clock_out) < strtotime($user_shift->out_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->out_time) - strtotime($out->clock_out)) : 0;
			$tag .= ($early_leaving) ? $this->attendanceTag('early_leaving') : '';
			$total_early_leaving += $early_leaving;

			foreach($records as $record){
				if($record->clock_in >= $user_shift->out_time && $record->clock_out != null)
					$overtime += strtotime($record->clock_out) - strtotime($record->clock_in);
				elseif($record->clock_in < $user_shift->out_time && $record->clock_out > $user_shift->out_time)
					$overtime += strtotime($record->clock_out) - strtotime($user_shift->out_time);
			}
			$tag .= ($overtime) ? $this->attendanceTag('overtime') : '';
			$total_overtime += $overtime;

			foreach($records as $record)
				$working += ($record->clock_out != null) ? abs(strtotime($record->clock_out) - strtotime($record->clock_in)) : 0;
			$total_working += $working;

			$rest = (isset($in) && $out->clock_out != null) ? (abs(strtotime($out->clock_out) - strtotime($in->clock_in)) - $working) : 0;
			$total_rest += $rest;

			$holiday = $holidays->where('date',$date)->first();

			if(isset($in))
				$attendance = 'P';
			elseif(count($half_day_leave_approved) && in_array($date,$half_day_leave_approved))
				$attendance = 'HD';
			elseif(count($leave_approved) && in_array($date,$leave_approved))
				$attendance = 'L';
			elseif($holiday)
				$attendance = 'H';
			elseif(!$holiday && $date < date('Y-m-d'))
				$attendance = 'A';
			else
				$attendance = '';

			$rows[] = array(
					$this->attendanceLabel($attendance),
					showDate($date),
					(isset($in)) ? showTime($in->clock_in) : '-',
					(isset($out)) ? showTime($out->clock_out) : '-',
					showDuration($late),
					showDuration($early_leaving),
					showDuration($overtime),
					showDuration($working),
					showDuration($rest),
					$tag
				);

			$raw_graph_data[] = array(
				'date' => showDate($date),
				'late' => ($late) ? $late/60 : 0,
				'early_leaving' => ($early_leaving) ? $early_leaving/60 : 0,
				'overtime' => ($overtime) ? $overtime/60 : 0,
				'working' => ($working) ? $working/60 : 0,
				'rest' => ($rest) ? $rest/60 : 0,
			);

			unset($tag);
			$date = date('Y-m-d',strtotime($date . ' +1 days'));
        }

        $summary = [
        	'late' => showDuration($late),
        	'early_leaving' => showDuration($early_leaving),
        	'overtime' => showDuration($overtime),
        	'working' => showDuration($working),
        	'rest' => showDuration($rest),
        ];

        $list['aaData'] = $rows;
        $list['foot'] = '<tr>
			<th colspan="4"></th>
			<th>'.showDuration($total_late).'</th>
			<th>'.showDuration($total_early_leaving).'</th>
			<th>'.showDuration($total_overtime).'</th>
			<th>'.showDuration($total_working).'</th>
			<th>'.showDuration($total_rest).'</th>
			<th></th>
		</tr>';

		$ydata = array();
		$late_xdata = array();
		$early_leaving_xdata = array();
		$overtime_xdata = array();
		$working_xdata = array();
		$rest_xdata = array();
		foreach($raw_graph_data as $data){
			$ydata[] = $data['date'];
			$late_xdata[] = $data['late'];
			$early_leaving_xdata[] = $data['early_leaving'];
			$overtime_xdata[] = $data['overtime'];
			$working_xdata[] = $data['working'];
			$rest_xdata[] = $data['rest'];
		}

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($ydata) + $extra_height;

        $list['graph'] = [
            'date_wise_attendance' => [
            	'late' => [
            		'title_text' => $user->name_with_designation_and_department.' : '.trans('messages.late').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $late_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.minute').' '.trans('messages.late'),
	                'height' => $height,
            	],
            	'early_leaving' => [
            		'title_text' => $user->name_with_designation_and_department.' : '.trans('messages.early_leaving').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $early_leaving_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.minute').' '.trans('messages.early_leaving'),
	                'height' => $height,
            	],
            	'overtime' => [
            		'title_text' => $user->name_with_designation_and_department.' : '.trans('messages.overtime').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $overtime_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.minute').' '.trans('messages.overtime'),
	                'height' => $height,
            	],
            	'working' => [
            		'title_text' => $user->name_with_designation_and_department.' : '.trans('messages.working').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $working_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.minute').' '.trans('messages.working'),
	                'height' => $height,
            	],
            	'rest' => [
            		'title_text' => $user->name_with_designation_and_department.' : '.trans('messages.rest').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $rest_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.minute').' '.trans('messages.rest'),
	                'height' => $height,
            	]
            ]
        ];

        return json_encode($list);
	}

	public function userWiseSummaryAttendance(){
		$data = array(
	        		trans('messages.user'),
	        		trans('messages.total').' '.trans('messages.work'),
	        		trans('messages.total').' '.trans('messages.rest'),
	        		trans('messages.overtime'),
	        		trans('messages.late'),
	        		trans('messages.early_leaving'),
	        		trans('messages.present'),
	        		trans('messages.absent'),
	        		trans('messages.holiday'),
	        		trans('messages.half').' '.trans('messages.day'),
	        		trans('messages.leave'),
	        		trans('messages.late').' '.trans('messages.count'),
	        		trans('messages.overtime').' '.trans('messages.count'),
	        		trans('messages.early_leaving').' '.trans('messages.count')
        		);

		$table_data['user-wise-summary-attendance-table'] = array(
				'source' => 'user-wise-summary-attendance',
				'title' => toWordTranslate('user-wise-summary-attendance-list'),
				'id' => 'user_wise_summary_attendance_table',
				'data' => $data,
				'form' => 'user-wise-summary-attendance-form'
			);

        $designations = childDesignation();
        $locations = childLocation();
		$assets = ['datatable','graph'];
		$menu = 'attendance,report';
		$current_report = 'user-wise-summary-attendance';
		return view('clock.user_wise_summary_attendance',compact('table_data','assets','menu','current_report','designations','locations'));
	}

	public function userWiseSummaryAttendanceLists(Request $request){
		$from_date = $request->input('from_date') ? : date('Y-m-d');
		$to_date = $request->input('to_date') ? : date('Y-m-d');

		$user_query = getAccessibleUser(\Auth::user()->id,1);

		if($request->input('designation_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('designation_id',$request->input('designation_id'));
			});

		if($request->input('location_id'))
			$user_query->whereHas('profile',function($query) use($request){
				$query->whereIn('location_id',$request->input('location_id'));
			});

		$users = $user_query->get();

        $clocks = \App\Clock::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $holidays = \App\Holiday::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $leaves = \App\Leave::whereStatus('approved')->get();
        $raw_data = array();

        foreach($users as $user)
        {
        	$total_late = $total_early_leaving = $total_overtime = $total_working = $total_rest = 0;
        	$tag_count = array();
	        $date = $from_date;
	        $attendance = [];

	        $half_day_leave_approved = array();
	        $full_day_leave_approved = array();
	        foreach($leaves->where('user_id',$user->id)->all() as $leave){
	            $leave_date_approved = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
	            foreach($leave_date_approved as $value){
	            	if($leave->LeaveType->is_half_day)
	                	$half_day_leave_approved[] = $value;
	                else
	                	$full_day_leave_approved[] = $value;
	            }
	        }

        	while($date <= $to_date){

        		$late = $early_leaving = $overtime = $working = $rest = 0;
	        	$user_shift = getShift($date,$user->id);
	        	$user_shift->in_time = $date.' '.$user_shift->in_time;
	        	$user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

	        	$out = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->last();
	        	$in = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->first();
				$records = $clocks->where('date',$date)->where('user_id',$user->id)->all();

				$late = (isset($in) && (strtotime($in->clock_in) > strtotime($user_shift->in_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->in_time) - strtotime($in->clock_in)) : 0;
				$total_late += $late;

				if($late)
					$tag_count[] = 'L';

				$early_leaving = (isset($out) && $out->clock_out != null && (strtotime($out->clock_out) < strtotime($user_shift->out_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->out_time) - strtotime($out->clock_out)) : 0;
				$total_early_leaving += $early_leaving;

				if($early_leaving)
					$tag_count[] = 'E';

				foreach($records as $record){
					if($record->clock_in >= $user_shift->out_time && $record->clock_out != null)
						$overtime += strtotime($record->clock_out) - strtotime($record->clock_in);
					elseif($record->clock_in < $user_shift->out_time && $record->clock_out > $user_shift->out_time)
						$overtime += strtotime($record->clock_out) - strtotime($user_shift->out_time);
				}
				$total_overtime += $overtime;

				if($overtime)
					$tag_count[] = 'O';

				foreach($records as $record)
					$working += ($record->clock_out != null) ? abs(strtotime($record->clock_out) - strtotime($record->clock_in)) : 0;
				$total_working += $working;

				$rest = (isset($in) && $out->clock_out != null) ? (abs(strtotime($out->clock_out) - strtotime($in->clock_in)) - $working) : 0;
				$total_rest += $rest;

				$holiday = $holidays->where('date',$date)->first();

				if(isset($in))
					$attendance[] = 'P';
				elseif(in_array($date,$half_day_leave_approved))
					$attendance[] = 'HD';
				elseif(in_array($date,$full_day_leave_approved))
					$attendance[] = 'L';
				elseif($holiday)
					$attendance[] = 'H';
				elseif(!$holiday && $date < date('Y-m-d'))
					$attendance[] = 'A';
				else
					$attendance[] = '';

				$date = date('Y-m-d',strtotime($date . ' +1 days'));
        	}

			$tag_count = array_count_values($tag_count);
			$attendance = array_count_values($attendance);

			$count_present = (array_key_exists('P', $attendance) ? $attendance['P'] : 0);
			$count_half_day = (array_key_exists('HD', $attendance) ? $attendance['HD'] : 0);
			$count_holiday = (array_key_exists('H', $attendance) ? $attendance['H'] : 0);
			$count_leave = (array_key_exists('L', $attendance) ? $attendance['L'] : 0);
			$count_absent = (array_key_exists('A', $attendance) ? $attendance['A'] : 0);
			$count_late = (array_key_exists('L', $tag_count) ? $tag_count['L'] : 0);
			$count_overtime = (array_key_exists('O', $tag_count) ? $tag_count['O'] : 0);
			$count_early = (array_key_exists('E', $tag_count) ? $tag_count['E'] : 0);

			$row = array(
				$user->name_with_designation_and_department,
				showDuration($total_working),
				showDuration($total_rest),
				showDuration($total_overtime),
				showDuration($total_late),
				showDuration($total_early_leaving),
				$count_present,
				$count_absent,
				$count_holiday,
				$count_half_day,
				$count_leave,
				$count_late,
				$count_overtime,
				$count_early,
			);

			$raw_graph_data[] = array(
				'name' => $user->full_name,
				'late' => ($total_late) ? $total_late/60 : 0,
				'early_leaving' => ($total_early_leaving) ? $total_early_leaving/60 : 0,
				'overtime' => ($total_overtime) ? $total_overtime/60 : 0,
				'working' => ($total_working) ? $total_working/60 : 0,
				'rest' => ($total_rest) ? $total_rest/60 : 0,
				'present' => $count_present,
				'absent' => $count_absent,
				'half_day' => $count_half_day,
				'leave' => $count_leave
			);

        	$rows[] = $row;
        	unset($tag_count);
        	unset($attendance);
        	unset($half_day_leave_approved);
        	unset($full_day_leave_approved);
        }
        $list['aaData'] = $rows;

		$ydata = array();
		$late_xdata = array();
		$early_leaving_xdata = array();
		$overtime_xdata = array();
		$working_xdata = array();
		$rest_xdata = array();
		$present_xdata = array();
		$absent_xdata = array();
		$leave_xdata = array();
		$half_day_xdata = array();
		foreach($raw_graph_data as $data){
			$ydata[] = $data['name'];
			$late_xdata[] = $data['late'];
			$early_leaving_xdata[] = $data['early_leaving'];
			$overtime_xdata[] = $data['overtime'];
			$working_xdata[] = $data['working'];
			$rest_xdata[] = $data['rest'];
			$present_xdata[] = $data['present'];
			$absent_xdata[] = $data['absent'];
			$leave_xdata[] = $data['leave'];
			$half_day_xdata[] = $data['half_day'];
		}

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($ydata) + $extra_height;

        $list['graph'] = [
            'user_wise_summary_attendance' => [
            	'late' => [
            		'title_text' => trans('messages.late').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $late_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.late'),
	                'height' => $height,
            	],
            	'early_leaving' => [
            		'title_text' => trans('messages.early_leaving').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $early_leaving_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.early_leaving'),
	                'height' => $height,
            	],
            	'overtime' => [
            		'title_text' => trans('messages.overtime').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $overtime_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.overtime'),
	                'height' => $height,
            	],
            	'working' => [
            		'title_text' => trans('messages.working').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $working_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.working'),
	                'height' => $height,
            	],
            	'rest' => [
            		'title_text' => trans('messages.rest').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $rest_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.minute').' '.trans('messages.rest'),
	                'height' => $height,
            	],
            	'present' => [
            		'title_text' => trans('messages.present').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $present_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.day').' '.trans('messages.present'),
	                'height' => $height,
            	],
            	'absent' => [
            		'title_text' => trans('messages.absent').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $absent_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.day').' '.trans('messages.absent'),
	                'height' => $height,
            	],
            	'leave' => [
            		'title_text' => trans('messages.leave').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $leave_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.day').' '.trans('messages.leave'),
	                'height' => $height,
            	],
            	'half_day' => [
            		'title_text' => trans('messages.half').' '.trans('messages.day').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'name' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $half_day_xdata,
	                'legend' => [trans('messages.user')],
	                'title' => trans('messages.day').' '.trans('messages.half').' '.trans('messages.day'),
	                'height' => $height,
            	]
            ]
        ];

        return json_encode($list);
	}

	public function dateWiseSummaryAttendance(){
		$data = array(
	        		trans('messages.date'),
	        		trans('messages.present'),
	        		trans('messages.absent'),
	        		trans('messages.holiday'),
	        		trans('messages.half').' '.trans('messages.day'),
	        		trans('messages.leave'),
	        		trans('messages.late').' '.trans('messages.count'),
	        		trans('messages.overtime').' '.trans('messages.count'),
	        		trans('messages.early_leaving').' '.trans('messages.count')
        		);

		$table_data['date-wise-summary-attendance-table'] = array(
				'source' => 'date-wise-summary-attendance',
				'title' => toWordTranslate('date-wise-summary-attendance-list'),
				'id' => 'date_wise_summary_attendance_table',
				'data' => $data,
				'form' => 'date-wise-summary-attendance-form'
			);

		$assets = ['datatable','graph'];
		$menu = 'attendance,report';
		$current_report = 'date-wise-summary-attendance';
		return view('clock.date_wise_summary_attendance',compact('table_data','assets','menu','current_report'));
	}

	public function dateWiseSummaryAttendanceLists(Request $request){
		$from_date = $request->input('from_date') ? : date('Y-m-d');
		$to_date = $request->input('to_date') ? : date('Y-m-d');

		if($request->input('type') == 'weekly-attendance-statistics-graph'){
			$to_date = date('Y-m-d');
			$from_date = date('Y-m-d',strtotime($to_date . ' -6 days'));
		}

		$user_query = getAccessibleUser(\Auth::user()->id,1);
		$users = $user_query->get();

        $clocks = \App\Clock::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $holidays = \App\Holiday::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $leaves = \App\Leave::whereStatus('approved')->get();
        $raw_data = array();
        $rows = array();

	    $date = $from_date;
        while($date <= $to_date){
        	$late = $early_leaving = $overtime = $working = $rest = 0;
        	$tag_count = array();
        	$attendance = array();

        	foreach($users as $user){
        		$half_day_leave = 0;
        		$full_day_leave = 0;

        		foreach($leaves->where('user_id',$user->id)->all() as $leave){
        			$leave_date_approved = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
        			if(in_array($date, $leave_date_approved) && $leave->LeaveType->is_half_day)
        				$half_day_leave = 1;
        			elseif(in_array($date, $leave_date_approved) && !$leave->LeaveType->is_half_day)
        				$full_day_leave = 1;
        		}

	        	$user_shift = getShift($date,$user->id);
	        	$user_shift->in_time = $date.' '.$user_shift->in_time;
	        	$user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

	        	$out = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->last();
	        	$in = $clocks->where('date',$date)->where('user_id',$user->id)->sortBy('clock_in')->first();
				$records = $clocks->where('date',$date)->where('user_id',$user->id)->all();

				$late = (isset($in) && (strtotime($in->clock_in) > strtotime($user_shift->in_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->in_time) - strtotime($in->clock_in)) : 0;
				$early_leaving = (isset($out) && $out->clock_out != null && (strtotime($out->clock_out) < strtotime($user_shift->out_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->out_time) - strtotime($out->clock_out)) : 0;

				foreach($records as $record){
					if($record->clock_in >= $user_shift->out_time && $record->clock_out != null)
						$overtime += strtotime($record->clock_out) - strtotime($record->clock_in);
					elseif($record->clock_in < $user_shift->out_time && $record->clock_out > $user_shift->out_time)
						$overtime += strtotime($record->clock_out) - strtotime($user_shift->out_time);
				}

				$holiday = $holidays->where('date',$date)->first();

				if(isset($in))
					$attendance[] = 'P';
				elseif($half_day_leave)
					$attendance[] = 'HD';
				elseif($full_day_leave)
					$attendance[] = 'L';
				elseif($holiday)
					$attendance[] = 'H';
				elseif(!$holiday && $date < date('Y-m-d'))
					$attendance[] = 'A';
				else
					$attendance[] = '';

				if($late)
					$tag_count[] = 'L';
				if($early_leaving)
					$tag_count[] = 'E';
				if($overtime)
					$tag_count[] = 'O';
        	}

			$tag_count = array_count_values($tag_count);
			$attendance = array_count_values($attendance);

			$count_present = (array_key_exists('P', $attendance) ? $attendance['P'] : 0);
			$count_half_day = (array_key_exists('HD', $attendance) ? $attendance['HD'] : 0);
			$count_holiday = (array_key_exists('H', $attendance) ? $attendance['H'] : 0);
			$count_leave = (array_key_exists('L', $attendance) ? $attendance['L'] : 0);
			$count_absent = (array_key_exists('A', $attendance) ? $attendance['A'] : 0);
			$count_late = (array_key_exists('L', $tag_count) ? $tag_count['L'] : 0);
			$count_overtime = (array_key_exists('O', $tag_count) ? $tag_count['O'] : 0);
			$count_early = (array_key_exists('E', $tag_count) ? $tag_count['E'] : 0);

			$row = array(
				showDate($date),
				$count_present,
				$count_absent,
				$count_holiday,
				$count_half_day,
				$count_leave,
				$count_late,
				$count_overtime,
				$count_early,
			);

			$raw_data['present'][] = $count_present;
			$raw_data['absent'][] = $count_absent;
			$raw_data['holiday'][] = $count_holiday;
			$raw_data['half_day'][] = $count_half_day;
			$raw_data['leave'][] = $count_leave;
			$raw_data['late'][] = $count_late;
			$raw_data['overtime'][] = $count_overtime;
			$raw_data['early_leaving'][] = $count_early;

			$raw_graph_data[] = array(
				'date' => showDate($date),
				'late' => $count_late,
				'early_leaving' => $count_early,
				'overtime' => $count_overtime,
				'present' => $count_present,
				'absent' => $count_absent,
				'half_day' => $count_half_day,
				'leave' => $count_leave
			);

        	$rows[] = $row;
        	unset($tag_count);
        	unset($attendance);
			$date = date('Y-m-d',strtotime($date . ' +1 days'));
        }
        $list['aaData'] = $rows;

		if($request->input('type') == 'weekly-attendance-statistics-graph'){
			$dates = getDateInArray($from_date,$to_date,1);
			$legend = [
				trans('messages.present').' '.trans('messages.count'),
				trans('messages.absent').' '.trans('messages.count'),
				trans('messages.holiday').' '.trans('messages.count'),
				trans('messages.half').' '.trans('messages.day').' '.trans('messages.count'),
				trans('messages.leave').' '.trans('messages.count'),
				trans('messages.late').' '.trans('messages.count'),
				trans('messages.overtime').' '.trans('messages.count'),
				trans('messages.early_leaving').' '.trans('messages.count')
			];
			$graph_data = array();
			foreach($raw_data as $key => $value){
				$graph_data[] = array(
						'name' => toWordTranslate($key).' '.trans('messages.count'),
						'type' => 'line',
						'data' => $value
					);
			}

			$list['dashboard_graph']['title_text'] = toWordTranslate('weekly-attendance-statistics-from').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date);
			$list['dashboard_graph']['title'] = $dates;
			$list['dashboard_graph']['legend'] = $legend;
			$list['dashboard_graph']['data'] = $graph_data;
		}

		$ydata = array();
		$late_xdata = array();
		$early_leaving_xdata = array();
		$overtime_xdata = array();
		$present_xdata = array();
		$absent_xdata = array();
		$leave_xdata = array();
		$half_day_xdata = array();
		foreach($raw_graph_data as $data){
			$ydata[] = $data['date'];
			$late_xdata[] = $data['late'];
			$early_leaving_xdata[] = $data['early_leaving'];
			$overtime_xdata[] = $data['overtime'];
			$present_xdata[] = $data['present'];
			$absent_xdata[] = $data['absent'];
			$leave_xdata[] = $data['leave'];
			$half_day_xdata[] = $data['half_day'];
		}

		$record_height = 50;
		$extra_height = 100;
		$height = $record_height*count($ydata) + $extra_height;

        $list['graph'] = [
            'date_wise_summary_attendance' => [
            	'late' => [
            		'title_text' => trans('messages.late').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $late_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.late').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'early_leaving' => [
            		'title_text' => trans('messages.early_leaving').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $early_leaving_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.early_leaving').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'overtime' => [
            		'title_text' => trans('messages.overtime').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $overtime_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.overtime').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'present' => [
            		'title_text' => trans('messages.present').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('date.to').' '.showDate($to_date),
            		'date' => trans('messages.user'),
	                'ydata' => $ydata,
	                'xdata' => $present_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.present').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'absent' => [
            		'title_text' => trans('messages.absent').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $absent_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.absent').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'leave' => [
            		'title_text' => trans('messages.leave').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $leave_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.leave').' '.trans('messages.count'),
	                'height' => $height,
            	],
            	'half_day' => [
            		'title_text' => trans('messages.half').' '.trans('messages.day').' '.trans('messages.statistics').' '.showDate($from_date).' '.trans('messages.to').' '.showDate($to_date),
            		'date' => trans('messages.date'),
	                'ydata' => $ydata,
	                'xdata' => $half_day_xdata,
	                'legend' => [trans('messages.date')],
	                'title' => trans('messages.half').' '.trans('messages.day').' '.trans('messages.count'),
	                'height' => $height,
            	]
            ]
        ];

        return json_encode($list);
	}

	public function updateAttendance(Request $request){
		$user_id = ($request->input('user_id')) ? : \Auth::user()->id;
		$date = ($request->input('date')) ? : date('Y-m-d');
		$default_datetimepicker_date = $date;

		if(config('config.enable_attendance_auto_lock') && $date < date('Y-m-d',strtotime(date('Y-m-d') . ' -'.config('config.attendance_auto_lock_days').' days')) && !defaultRole())
			return redirect()->back()->withErrors(trans('messages.attendance_locked'));

		$user = \App\User::find($user_id);

		$clocks = Clock::where('date','=',$date)
			->whereUserId($user_id)
			->orderBy('clock_in')
			->get();

		if(!Entrust::can('edit-attendance'))
			return redirect('/dashboard')->withErrors(trans('messages.permission_denied'));

		$users = getAccessibleUserList();

    	$user_shift = getShift($date,$user_id);
      if(!$user_shift)
        return redirect()->back()->withInput()->withErrors(trans('messages.shift_not_defined'));

    	$user_shift->in_time = $date.' '.$user_shift->in_time;
    	$user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

    	$holiday = \App\Holiday::where('date','=',$date)->first();
    	$label = '<span class="badge badge-success">'.trans('messages.working').' '.trans('messages.day').'</span>';
    	if($holiday)
    		$label = '<span class="badge badge-info">'.trans('messages.holiday').': '.$holiday->description.'</span>';

		$leaves = \App\Leave::whereUserId($user_id)->whereStatus('approved')->where(function($query) use($date){
			$query->where('from_date','>=',$date)
			->orWhere('to_date','<=',$date)
			->orWhere(function($query1) use($date){
				$query1->where('from_date','<',$date)
				->where('to_date','>',$date);
			});
		})->get();
        $leave_approved = array();
        foreach($leaves as $leave){
            $leave_approved_dates = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
            foreach($leave_approved_dates as $leave_approved_date)
                $leave_approved[] = $leave_approved_date;
        }

    	if(in_array($date,$leave_approved))
    		$label = '<span class="badge badge-danger">'.trans('messages.on').' '.trans('messages.leave').'</span>';

        $assets = ['datetimepicker'];
        $menu = 'attendance,update_attendance';
        return view('clock.update_attendance',compact('users','assets','user','date','clocks','user_shift','menu','label','default_datetimepicker_date'));
	}

	public function validateClock($clock_in,$clock_out,$user_id,$date,$clock_id){

		if(!$user_id){
	        $response = ['message' => trans('messages.no_user_found'), 'status' => 'error'];
	    	return $response;
		}

		$next_date = date('Y-m-d',strtotime($date.' +1 days'));

        $shift = getShift($date,$user_id);
       	$next_date_shift = getShift($next_date);

		$query1 = Clock::whereUserId($user_id)->where('date','=',$date)->where('clock_in','<=',$clock_in)->where('clock_out','>=',$clock_in);
		if($clock_out)
		$query2 = Clock::whereUserId($user_id)->where('date','=',$date)->where('clock_in','<=',$clock_out)->where('clock_out','>=',$clock_out);

		if($clock_id){
			$query1->where('id','!=',$clock_id);
			if($clock_out)
			$query2->where('id','!=',$clock_id);
		}

		$clock_in_count = $query1->count();
		if($clock_out)
		$clock_out_count = $query2->count();

		if($clock_out){
	    	$in_out_clock = Clock::whereUserId($user_id)->where('clock_in','>=',$clock_in)->where('clock_in','<=',$clock_out)->count();
		}

		if($date != date('Y-m-d') && !$clock_out)
	        $response = ['message' => trans('messages.clock_out_is_mandatory_for_other_date'), 'status' => 'error'];
		elseif(!$clock_out && \App\Clock::whereUserId($user_id)->where('date','=',$date)->where('clock_in','>=',$clock_in)->count())
	        $response = ['message' => trans('messages.clock_already_exists_after_given_time'), 'status' => 'error'];
		elseif($clock_in < $date)
	        $response = ['message' => trans('messages.clock_in_less_than_current_date'), 'status' => 'error'];
		elseif(!$shift->overnight && $clock_in >= $next_date)
	        $response = ['message' => trans('messages.clock_in_greater_than_current_date'), 'status' => 'error'];
		elseif($shift->overnight && $clock_in >= $next_date.' '.$next_date_shift->in_time)
	        $response = ['message' => trans('messages.clock_in_greater_than_current_date_overtime'), 'status' => 'error'];
		elseif($clock_in_count > 0)
	        $response = ['message' => trans('messages.clock_in_between_time'), 'status' => 'error'];
	    elseif($clock_out && $in_out_clock)
	        $response = ['message' => trans('messages.clock_in_clock_out_outside_time'), 'status' => 'error'];
		elseif($clock_out && $clock_out_count)
	        $response = ['message' => trans('messages.clock_out_between_time'), 'status' => 'error'];
		elseif($clock_out && $clock_in > $clock_out)
	        $response = ['message' => trans('messages.out_time_not_less_than_in_time'), 'status' => 'error'];
    	elseif($clock_out && !$shift->overnight && $clock_out >= $next_date)
	        $response = ['message' => trans('messages.clock_out_greater_than_current_date'), 'status' => 'error'];
    	elseif($clock_out && $shift->overnight && $clock_out >= $next_date.' '.$next_date_shift->in_time)
	        $response = ['message' => trans('messages.clock_out_greater_than_current_date_overtime'), 'status' => 'error'];
	    else
	    	$response = ['status' => 'success'];

	    return $response;
	}

	public function clock(Request $request,$user_id,$date,$clock_id = null){
		if(config('config.enable_attendance_auto_lock') && $date < date('Y-m-d',strtotime(date('Y-m-d') . ' -'.config('config.attendance_auto_lock_days').' days')) && !defaultRole())
			return response()->json(['message' => trans('messages.attendance_locked'),'status' => 'error']);

        $validation = Validator::make($request->all(),[
            'clock_in' => 'required'
        ]);

        if($validation->fails())
	        return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        if($clock_id != null)
        	$clock = Clock::find($clock_id);

        if($clock_id != null && !$clock)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

		$clock_in = date('Y-m-d H:i',strtotime($request->input('clock_in')));
		$clock_out = ($request->input('clock_out')) ? date('Y-m-d H:i',strtotime($request->input('clock_out'))) : null;

		$response = $this->validateClock($clock_in,$clock_out,$user_id,$date,$clock_id);

    	if(isset($response) && $response['status'] == 'error')
	        return response()->json($response);

    	if($clock_id == null){
	        $clock = new Clock;
	        $clock->date = $date;
        	$clock->user_id = $user_id;
    	}

        $clock->clock_in = $clock_in;
        $clock->clock_out = $clock_out;
        $clock->save();

        $this->logActivity(['module' => 'attendance','module_id' => $clock->id,'activity' => 'updated']);
        return response()->json(['message' => trans('messages.attendance').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function attendanceLists(Request $request){
		if(!in_array($request->input('user_id'),getAccessibleUserId()))
			return;

		$clocks = Clock::whereUserId($request->input('user_id'))->where('date','=',$request->input('date'))->get();
		return view('clock.attendance_list',compact('clocks'))->render();
	}

	public function edit(Clock $clock){
		if(!in_array($clock->user_id,getAccessibleUserId()))
			return view('global.error',['message' => trans('messages.permission_denied')]);

		return view('clock.edit',compact('clock'));
	}

	public function destroy(Request $request, Clock $clock){
		if(config('config.enable_attendance_auto_lock') && $clock->date < date('Y-m-d',strtotime(date('Y-m-d') . ' -'.config('config.attendance_auto_lock_days').' days')) && !defaultRole())
			return response()->json(['message' => trans('messages.attendance_locked'),'status' => 'error']);

		if(!in_array($clock->user_id,getAccessibleUserId()))
			return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $this->logActivity(['module' => 'attendance','unique_id' => $clock->id,'activity' => 'deleted']);

        $clock->delete();

        return response()->json(['message' => trans('messages.attendance').' '.trans('messages.deleted'), 'status' => 'success']);
	}

    public function bulkUpload(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $input[] = $request->input('a');
        $input[] = $request->input('b');
        $input[] = $request->input('c');
        $input[] = $request->input('d');
        $input[] = $request->input('e');
        $input[] = $request->input('f');

        $unique_input = array_unique($input);

        if(count($unique_input) < 6)
            return response()->json(['message' => trans('messages.duplicate_column_found'), 'status' => 'error']);

        $file = session('user_upload_file');
        $filename_array = explode('.', $file);
        $filename = $filename_array[0];

        $filename_extension = storage_path().config('constant.storage_root').'temp_bulk_upload/'.$file;

        if(!session()->has('user_upload_file') || !\File::exists($filename_extension))
            return response()->json(['message' => trans('messages.something_wrong'), 'status' => 'error','redirect' => '/home']);

        include('../app/Helper/ExcelReader/SpreadsheetReader.php');

        $xls_datas = array();
        $Reader = new \SpreadsheetReader($filename_extension);
        foreach ($Reader as $key => $row){
            $xls_datas[] = array(
				'employee_code' => $row[0],
				'date' => $row[1],
				'clock_in' => ($row[2] && $row[3]) ? $row[2].' '.$row[3] : '',
				'clock_out' => ($row[4] && $row[5]) ? $row[4].' '.$row[5] : ''
            );
        }

		$users = \App\Profile::all()->pluck('user_id','employee_code')->all();
        $data = array();
        $data_fails = array();

        $clock_id = null;
        foreach($xls_datas as $xls_data)
        {
			$employee_code = $xls_data['employee_code'];
			$user_id = (isset($users[$employee_code])) ? $users[$employee_code] : NULL;
			$date = date('Y-m-d',strtotime($xls_data['date']));
			$clock_in = date('Y-m-d H:i',strtotime($xls_data['clock_in']));
			$clock_out = ($xls_data['clock_out']) ? date('Y-m-d H:i',strtotime($xls_data['clock_out'])) : NULL;

			$response = $this->validateClock($clock_in,$clock_out,$user_id,$date,$clock_id);

	      	if($response['status'] == 'success')
	      		$data[] = array(
		      		'user_id' => $user_id,
		      		'date' => $date,
		      		'clock_in' => $clock_in,
		      		'clock_out' => $clock_out
	      		);
	  		else
	  			$data_fails[] = array(
	  				'employee_code' => $employee_code,
	  				'date' => $date,
	  				'clock_in_date' => date('Y-m-d',strtotime($clock_in)),
	  				'clock_in_time' => date('H:i A',strtotime($clock_in)),
	      			'clock_out_date' => date('Y-m-d',strtotime($clock_out)),
	      			'clock_out_time' => date('H:i A',strtotime($clock_out)),
	      			'error' => $response['message']
	  			);
        }

        $bulk_upload = new \App\BulkUpload;
        $bulk_upload->user_id = \Auth::user()->id;
        $bulk_upload->module = 'attendance';
        $bulk_upload->uuid = $filename;
        $bulk_upload->total = count($xls_datas);
        $bulk_upload->uploaded = count($data);
        $bulk_upload->rejected = count($data_fails);
        $bulk_upload->save();
        \Storage::copy('temp_bulk_upload/'.$file, 'bulk_upload/'.$bulk_upload->uuid.'.csv');

        if(count($data))
            Clock::insert($data);
        if(count($data_fails))
            generateCSV($data_fails,$bulk_upload->uuid.'.csv');

        session()->forget('user_upload_file');

        if(count($data))
            $this->logActivity(['module' => 'upload','module_id' => $bulk_upload->id,'sub_module' => 'attendance','activity' => 'uploaded']);
        if(count($data))
            return response()->json(['message' => trans('messages.attendance').' '.trans('messages.uploaded'), 'status' => 'success','redirect' => '/home']);

        return response()->json(['message' => trans('messages.attendance').' '.trans('messages.upload').' '.trans('messages.w_rejected').'.', 'status' => 'error','redirect' => '/home']);
    }
}
