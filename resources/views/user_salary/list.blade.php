
	@if($user->UserSalary->count())
		@foreach($user->UserSalary as $user_salary)
			<tr>
				<td>{!! showDate($user_salary->from_date) !!}</td>
				<td>{!! showDate($user_salary->to_date) !!}</td>
				<td>{{trans('messages.'.$user_salary->type)}}</td>
				<td>{{currency($user_salary->hourly_rate,1,$user_salary->currency_id)}}</td>
				@foreach($earning_salary_heads as $earning_salary_head)
					<td>
						{!! $user_salary->UserSalaryDetail->where('salary_head_id',$earning_salary_head->id)->count() ? currency($user_salary->UserSalaryDetail->where('salary_head_id',$earning_salary_head->id)->first()->amount,1,$user_salary->currency_id) : 0 !!}
					</td>
				@endforeach
				@foreach($deduction_salary_heads as $deduction_salary_head)
					<td>
						{!! $user_salary->UserSalaryDetail->where('salary_head_id',$deduction_salary_head->id)->count() ? currency($user_salary->UserSalaryDetail->where('salary_head_id',$deduction_salary_head->id)->first()->amount,1,$user_salary->currency_id) : 0 !!}
					</td>
				@endforeach
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-salary/{{$user_salary->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-salary/{{$user_salary->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-salary.destroy',$user_salary->id],['table-refresh' => 'user-salary-table','refresh-content' => 'load-user-detail'])!!}
					@endif
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="15">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif