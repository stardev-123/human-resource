@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/payroll">{!! trans('messages.payroll') !!}</a></li>
		    <li class="active">{!! $user->name_with_designation_and_department.' '.trans('messages.payroll').' '.trans('messages.from').' '.showDate($payroll->from_date).' '.trans('messages.to').' '.showDate($payroll->to_date) !!}</li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			<div class="col-sm-4">
				@include('payroll.summary')
			</div>
			<div class="col-sm-8">
				<div class="box-info">
					<h2><strong>{{trans('messages.payroll')}}</strong>
					<div class="additional-btn">
						<a href="/payroll/{{$payroll->uuid}}/generate/mail" data-toggle="tooltip" title="{{trans('messages.mail')}}"><button class="btn btn-xs btn-success"><i class="fa fa-envelope icon"></i></button></a>
						<a href="/payroll/{{$payroll->uuid}}/generate/print" target="_blank" data-toggle="tooltip" title="{{trans('messages.print')}}"><button class="btn btn-xs btn-primary"><i class="fa fa-print icon"></i></button></a>
						<a href="/payroll/{{$payroll->uuid}}/generate/pdf" data-toggle="tooltip" title="{{trans('messages.generate').' PDF'}}"><button class="btn btn-xs btn-warning"><i class="fa fa-file-pdf-o icon"></i></button></a>
						<a href="#" data-href="/payroll/{{$payroll->id}}/edit" data-toggle="modal" data-target="#myModal"><button class="btn btn-xs btn-default" data-toggle="tooltip" title="{{trans('messages.edit')}}"><i class="fa fa-edit icon"></i></button></a>
						{!!delete_form(['payroll.destroy',$payroll->id],['redirect' => '/payroll'])!!}
					</div>
					</h2>
					<div class="table-responsive">
						<table class="table table-stripped table-hover table-bordered">
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
								<th>#</td>
								<th>{!! str_pad($payroll->id, 3, 0, STR_PAD_LEFT) !!}</th>
							</tr>
							@if(!$payroll->is_hourly)
							<tr>
								<td colspan = "2" valign="top" style="padding:0px;">
									<table class="table" style="border:0px">
										<thead>
											<tr>
												<th>{!! trans('messages.salary').' '.trans('messages.pay') !!} </th>
												<td align="right">{!! trans('messages.amount') !!} </td>
											</tr>
										</thead>
										<tbody>
										@foreach($earning_salary_heads as $earning_salary_head)
										<tr>
											<td>{!! $earning_salary_head->name !!}</td>
											<td align="right">{!! array_key_exists($earning_salary_head->id, $payroll_details) ? currency($payroll_details[$earning_salary_head->id],1,$payroll->currency_id) : 0 !!}</td>
										</tr>
										<?php $total_earning += array_key_exists($earning_salary_head->id, $payroll_details) ? ($payroll_details[$earning_salary_head->id]) : 0; ?>
										@endforeach
										@if($user_salary->overtime_hourly_rate)
										<tr>
											<td style="width:60%;">{!! trans('messages.overtime') !!}</td>
											<td style="text-align:right;width:40%;">{!! currency($payroll->overtime,1,$payroll->currency_id) !!}</td>
										</tr>
										<?php $total_earning += $payroll->overtime; ?>
										@endif
										</tbody>
									</table>
								</td>
								<td colspan = "2" valign="top" style="padding:0px;">
									<table class="table">
										<thead>
										<tr>
											<th>{!! trans('messages.salary').' '.trans('messages.deduction') !!} </th>
											<td align="right">{!! trans('messages.amount') !!} </td>
										</tr>
										</thead>
										<tbody>
										@foreach($deduction_salary_heads as $deduction_salary_head)
										<tr>
											<td>{!! $deduction_salary_head->name !!}</td>
											<td align="right">{!! array_key_exists($deduction_salary_head->id, $payroll_details) ? currency($payroll_details[$deduction_salary_head->id],1,$payroll->currency_id) : 0 !!}</td>
										</tr>
										<?php $total_deduction += array_key_exists($deduction_salary_head->id, $payroll_details) ? ($payroll_details[$deduction_salary_head->id]) : 0; ?>
										@endforeach
										@if($user_salary->late_hourly_rate)
										<tr>
											<td style="width:60%;">{!! trans('messages.late') !!}</td>
											<td style="text-align:right;width:40%;">{!! currency($payroll->late,1,$payroll->currency_id) !!}</td>
										</tr>
										<?php $total_deduction += $payroll->late; ?>
										@endif
										@if($user_salary->early_leaving_hourly_rate)
										<tr>
											<td style="width:60%;">{!! trans('messages.early_leaving') !!}</td>
											<td style="text-align:right;width:40%;">{!! currency($payroll->early_leaving,1,$payroll->currency_id) !!}</td>
										</tr>
										<?php $total_deduction += $payroll->early_leaving; ?>
										@endif
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="2" style="padding:0px;">
									<table class="table">
										<thead>
											<tr>
												<td class="strong-text">{!! trans('messages.total').' '.trans('messages.earning') !!} </td>
												<td class="pull-right strong-text">{!! currency($total_earning,1,$payroll->currency_id) !!}</td>
											</tr>
										</thead>
									</table>
								</td>
								<td colspan="2" style="padding:0px;">
									<table class="table">
										<thead>
											<tr>
												<td class="strong-text">{!! trans('messages.total').' '.trans('messages.deduction') !!} </td>
												<td class="pull-right strong-text">{!! currency($total_deduction,1,$payroll->currency_id) !!}</td>
											</tr>
										</thead>
									</table>
								</td>
							</tr>
							@else
							<tr>
								<?php $total_earning = $payroll->hourly; ?>
								<th>{!! trans('messages.hourly').' '.trans('messages.pay') !!}</th>
								<th colspan="3" style="text-align:right;">{!! currency($total_earning,1,$payroll->currency_id) !!}</th>
							</tr>
							@endif
							<tr>
								<th>{!! trans('messages.net').' '.trans('messages.pay') !!} </th>
								<th colspan="3">{!! currency(($total_earning-$total_deduction),1,$payroll->currency_id)." (".ucwords(numberToWord(currency(($total_earning-$total_deduction),0,$payroll->currency_id))).")" !!} </th>
							</tr>
						</table>
					</div>
				</div>
				@include('payroll.salary')
			</div>
		</div>
	@stop
