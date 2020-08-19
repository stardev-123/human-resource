<div class="box-info full">
	@if($user_salary->type == 'monthly')
		<h2><strong>{!! trans('messages.monthly') !!}</strong> {!! trans('messages.salary') !!} {!! $user->name_with_designation_and_department !!}</h2>
		<div class="row">
			<div class="col-md-6">
				<div class="table-responsive">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th colspan="2">{!! trans('messages.earning') !!}</th>
							</tr>
						</thead>
						<thead>
							<tr>
								<th>{!! trans('messages.head') !!}</th>
								<th>{!! trans('messages.amount') !!}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($salaries as $salary)
							@if($salary->SalaryHead->type == 'earning')
								<tr>
									<td>{!! $salary->SalaryHead->name !!}</td>
									<td>{!! currency($salary->amount,1,$user_salary->currency_id) !!}</td>
								</tr>
							@endif
							@endforeach
								<tr>
									<td>{!! trans('messages.overtime').' '.trans('messages.hourly') !!}</td>
									<td>{!! currency($user_salary->overtime_hourly_rate,1,$user_salary->currency_id).' / '.trans('messages.hour') !!}</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<div class="table-responsive">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th colspan="2">{!! trans('messages.deduction') !!}</th>
							</tr>
						</thead>
						<thead>
							<tr>
								<th>{!! trans('messages.head') !!}</th>
								<th>{!! trans('messages.amount') !!}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($salaries as $salary)
							@if($salary->SalaryHead->type == 'deduction')
								<tr>
									<td>{!! $salary->SalaryHead->name !!}</td>
									<td>{!! currency($salary->amount,1,$user_salary->currency_id) !!}</td>
								</tr>
							@endif
							@endforeach
								<tr>
									<td>{!! trans('messages.late').' '.trans('messages.deduction') !!}</td>
									<td>{!! currency($user_salary->late_hourly_rate,1,$user_salary->currency_id).' / '.trans('messages.hour') !!}</td>
								</tr>
								<tr>
									<td>{!! trans('messages.early_leaving').' '.trans('messages.deduction') !!}</td>
									<td>{!! currency($user_salary->early_leaving_hourly_rate,1,$user_salary->currency_id).' / '.trans('messages.hour') !!}</td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@else
		<h2><strong>{!! trans('messages.hourly') !!}</strong> {!! trans('messages.salary') !!} {!! $user->name_with_designation_and_department !!}</h2>
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>{!! trans('messages.salary').' '.trans('messages.head') !!}</th>
						<th>{!! trans('messages.amount') !!}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{!! trans('messages.hourly').' '.trans('messages.salary') !!}</td>
						<td>{!! currency($user_salary->hourly_rate,1,$user_salary->currency_id).' / '.trans('messages.hour') !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
		@endif
</div>
