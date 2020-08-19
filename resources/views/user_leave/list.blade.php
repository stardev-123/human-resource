
	@if($user->UserLeave->count())
		@foreach($user->UserLeave as $user_leave)
			<tr>
				<td>{!! showDate($user_leave->from_date) !!}</td>
				<td>{!! showDate($user_leave->to_date) !!}</td>
				@foreach($leave_types as $leave_type)
					<td>
						{!! $user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->count() ? ($user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first()->leave_used.'/'.$user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first()->leave_assigned) : 0 !!}
					</td>
				@endforeach
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-leave/{{$user_leave->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-leave/{{$user_leave->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-leave.destroy',$user_leave->id],['table-refresh' => 'user-leave-table','refresh-content' => 'load-user-detail'])!!}
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