	
		@if($leave->LeaveStatusDetail->count())
			@foreach($leave->LeaveStatusDetail as $leave_status_detail)
				<tr>
					<td>{{$leave_status_detail->Designation->designation_with_department}}</td>
					<td>
						@if($leave_status_detail->status == 'pending')
							<span class="label label-info">{{trans('messages.pending')}}</span>
						@elseif($leave_status_detail->status == 'rejected')
							<span class="label label-danger">{{trans('messages.w_rejected')}}</span>
						@elseif($leave_status_detail->status == 'approved')
							<span class="label label-success">{{trans('messages.w_approved')}}</span>
						@endif
					</td>
					<td>
						@if($leave_status_detail->status == 'approved')
							@if(count(explode(',',$leave->date_approved)) == dateDiff($leave->from_date,$leave->to_date))
								{{trans('messages.all').' '.trans('messages.date')}}
							@else
								<ol>
									@foreach(explode(',',$leave->date_approved) as $date_approved)
										<li>{{showDate($date_approved)}}</li>
									@endforeach
								</ol>
							@endif
						@else
							-
						@endif
					</td>
					<td>{{$leave_status_detail->remarks}}</td>
					<td>{{($leave_status_detail->status != null && $leave_status_detail->status != 'pending') ? showDateTime($leave_status_detail->updated_at) : ''}}</td>
				</tr>
			@endforeach	
		@else
			<tr>
				<td colspan="5">{{trans('messages.no_data_found')}}</td>
			</tr>
		@endif