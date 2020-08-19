<title>{!! config('config.application_name') ? : config('constants.default_title') !!}</title>
<style>
*{font-family:helvetica; font-size:12px;}
table.fancy {  font-size:12px; border-collapse: collapse;  width:100%;  margin:0 auto;  margin-bottom:10px; margin-top:10px;}
table.fancy th{  border: 1px #2e2e2e solid;  padding: 0.5em;  padding-left:10px; vertical-align:top;text-align: left;}
table.fancy td {  text-align: left;padding: 0.5em;  }
table.fancy caption {  margin-left: inherit;  margin-right: inherit;}
table.fancy tr:hover{}

table.fancy-detail {  font-size:12px; border-collapse: collapse;  width:100%;  margin:0 auto;  margin-bottom:10px; margin-top:10px;}
table.fancy-detail-detail-detail th{  border: 1px #2e2e2e solid;  padding: 0.5em;  padding-left:10px; vertical-align:top;text-align: left;}
table.fancy-detail-detail th, table.fancy-detail td  {  padding: 0.5em;  padding-left:10px; border:1px solid #2e2e2e;text-align: left;}
table.fancy-detail caption {  margin-left: inherit;  margin-right: inherit;}
table.fancy-detail tr:hover{}

</style>
<table border="0" style="width:100%;">
	<tr>
		<td style="width:40%;">
			{!! getCompanyLogo() !!}
		</td>
		<td style="text-align:right;width:60%;">
			<p style='font-size:16px; font-weight:bold;'>{!! config('config.company_name') !!}</h2>
			<p style='font-size:14px; font-weight:bold;'>{!! $company_address !!}</p>
			<p style=''>{!! trans('messages.email') !!} : {!! config('config.company_email') !!} | {!! trans('messages.phone') !!} : {!! config('config.company_phone') !!}</p>
		</td>
	</tr>
</table>
<table class="fancy">
	<tr>
		<th>{!! trans('messages.name') !!} </th>
		<th>{!! $user->full_name !!}</th>
		<th>{!! trans('messages.employee').' '.trans('messages.code') !!} </th>
		<th>{!! $user->Profile->employee_code !!}</th>
	</tr>
	<tr>
		<th>{!! trans('messages.department') !!} </th>
		<th>{!! $user->department_name !!}</th>
		<th>{!! trans('messages.designation') !!} </th>
		<th>{!! $user->designation_name !!}</th>
	</tr>
	<tr>
		<th>{!! trans('messages.duration') !!} </td>
		<th>{!! showDate($payroll->from_date).' '.trans('messages.to').' '.showDate($payroll->to_date) !!}</th>
		<th>{{ trans('messages.date_of').' '.trans('messages.payroll').' : '.showDate($payroll->date_of_payroll)}}</td>
		<th>#{!! str_pad($payroll->id, 3, 0, STR_PAD_LEFT) !!}</th>
	</tr>
</table>
@if(config('config.payroll_include_day_summary') && !$payroll->is_hourly)
<table class="fancy">
	<tr>
		<th>{!! trans('messages.absent') !!}</th>
		<th>{!! trans('messages.holiday') !!}</th>
		<th>{!! trans('messages.present') !!}</th>
		<th>{!! trans('messages.leave') !!}</th>
		<th>{!! trans('messages.late') !!}</th>
		<th>{!! trans('messages.overtime') !!}</th>
		<th>{!! trans('messages.early_leaving') !!}</th>
	</tr>
	<tr>
		<th>{!! $att_summary['A'] !!}</th>
		<th>{!! $att_summary['H'] !!}</th>
		<th>{!! $att_summary['P'] !!}</th>
		<th>{!! $att_summary['L'] !!}</th>
		<th>{!! $att_summary['Late'] !!}</th>
		<th>{!! $att_summary['Overtime'] !!}</th>
		<th>{!! $att_summary['Early'] !!}</th>
	</tr>
</table>
@endif
@if(config('config.payroll_include_hour_summary'))
<table class="fancy">
	<tr>
		<th>{!! trans('messages.total').' '.trans('messages.late') !!}</th>
		<th>{!! trans('messages.total').' '.trans('messages.early_leaving') !!}</th>
		<th>{!! trans('messages.total').' '.trans('messages.rest') !!}</th>
		<th>{!! trans('messages.total').' '.trans('messages.overtime') !!}</th>
		<th>{!! trans('messages.total').' '.trans('messages.work') !!}</th>
	</tr>
	<tr>
		<th>{!! array_key_exists('total_late',$summary) ? $summary['total_late'] : '-' !!}</th>
		<th>{!! array_key_exists('total_early_leaving',$summary) ? $summary['total_early_leaving'] : '-' !!}</th>
		<th>{!! array_key_exists('total_rest',$summary) ? $summary['total_rest'] : '-' !!}</th>
		<th>{!! array_key_exists('total_overtime',$summary) ? $summary['total_overtime'] : '-' !!}</th>
		<th>{!! array_key_exists('total_working',$summary) ? $summary['total_working'] : '-' !!}</th>
	</tr>
</table>
@endif
@if(config('config.payroll_include_leave_summary') && !$payroll->hourly_payroll)
<table class="fancy">
	<tr>
		@foreach($leave_types as $leave_type)
			<th>{!!$leave_type->name!!}</th>
		@endforeach
	</tr>
	<tr>
		@foreach($leave_types as $leave_type)
			<th>{!!$user_leave_data[$leave_type->id]['leave_used'].'/'.$user_leave_data[$leave_type->id]['leave_assigned']!!}</th>
		@endforeach
	</tr>
</table>
@endif

<table class="fancy">
	@if(!$payroll->is_hourly)
	<tr>
		<td colspan="2" valign="top" width="50%">
			<table class="fancy-detail">
				<tr>
					<th style="width:60%;">{!! trans('messages.earning').' '.trans('messages.pay') !!} </th>
					<th style="text-align:right;width:40%;">{!! trans('messages.amount') !!} </th>
				</tr>
				@foreach($earning_salary_heads as $earning_salary_head)
				<tr>
					<td style="width:60%;">{!! $earning_salary_head->name !!}</td>
					<td style="text-align:right;width:40%;">{!! array_key_exists($earning_salary_head->id, $payroll_details) ? currency($payroll_details[$earning_salary_head->id],1,$payroll->currency_id) : 0 !!}</td>
				</tr>
				<?php $total_earning += array_key_exists($earning_salary_head->id, $payroll_details) ? ($payroll_details[$earning_salary_head->id]) : 0; ?>
				@endforeach
				@if($payroll->overtime)
				<tr>
					<td style="width:60%;">{!! trans('messages.overtime') !!}</td>
					<td style="text-align:right;width:40%;">{!! currency($payroll->overtime,1,$payroll->currency_id) !!}</td>
				</tr>
				<?php $total_earning += $payroll->overtime; ?>
				@endif
			</table>
		</td>
		<td colspan="2" valign="top">
			<table class="fancy-detail">
				<tr>
					<th style="width:60%;">{!! trans('messages.salary').' '.trans('messages.deduction') !!} </th>
					<th style="text-align:right;width:40%;">{!! trans('messages.amount') !!} </th>
				</tr>
				@foreach($deduction_salary_heads as $deduction_salary_head)
				<tr>
					<td style="width:60%;">{!! $deduction_salary_head->name !!}</td>
					<td style="text-align:right;width:40%;">{!! array_key_exists($deduction_salary_head->id, $payroll_details) ? currency($payroll_details[$deduction_salary_head->id],1,$payroll->currency_id) : 0 !!}</td>
				</tr>
				<?php $total_deduction += array_key_exists($deduction_salary_head->id, $payroll_details) ? ($payroll_details[$deduction_salary_head->id]) : 0; ?>
				@endforeach
				@if($payroll->late)
				<tr>
					<td style="width:60%;">{!! trans('messages.late') !!}</td>
					<td style="text-align:right;width:40%;">{!! currency($payroll->late,1,$payroll->currency_id) !!}</td>
				</tr>
				<?php $total_deduction += $payroll->late; ?>
				@endif
				@if($payroll->early_leaving)
				<tr>
					<td style="width:60%;">{!! trans('messages.early_leaving') !!}</td>
					<td style="text-align:right;width:40%;">{!! currency($payroll->early_leaving,1,$payroll->currency_id) !!}</td>
				</tr>
				<?php $total_deduction += $payroll->early_leaving; ?>
				@endif
			</table>
		</td>
	</tr>
	<tr>
		<td colspan = "2">
			<table class="fancy-detail">
				<tr>
					<th style="width:60%;">{!! trans('messages.total').' '.trans('messages.earning') !!} </th>
					<th style="text-align:right;width:40%;">{!! currency($total_earning,1,$payroll->currency_id) !!}</th>
				</tr>
			</table>
		</td>
		<td colspan = "2">
			<table class="fancy-detail">
				<tr>
					<th style="width:60%;">{!! trans('messages.total').' '.trans('messages.deduction') !!} </th>
					<th style="text-align:right;width:40%;">{!! currency($total_deduction,1,$payroll->currency_id) !!}</th>
				</tr>
			</table>
		</td>
	</tr>
	@else
	<tr>
		<?php $total_earning = $payroll->hourly; ?>
		<th>{!! trans('messages.hourly').' '.trans('messages.total') !!}</th>
		<th colspan="3" style="text-align:right;">{!! currency($total_earning,1,$payroll->currency_id) !!}</th>
	</tr>
	@endif
	<tr>
		<th>{!! trans('messages.net').' '.trans('messages.total') !!} </th>
		<th colspan="3" style="text-align:right;">{!! currency(($total_earning-$total_deduction),1,$payroll->currency_id)." (".ucwords(numberToWord(currency(($total_earning-$total_deduction)))).")" !!} </th>
	</tr>
</table>
<p style='text-align:right;margin-top:30px;'>{!! trans('messages.authorized_signatory') !!}</p>
