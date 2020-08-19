		@if($job_application_status_details->count())
			@foreach($job_application_status_details as $job_application_status_detail)
				<tr>
					<td>{{trans('messages.'.$job_application_status_detail->status)}}</td>
					<td>{{$job_application_status_detail->remarks}}</td>
					<td>{{$job_application_status_detail->User->name_with_designation_and_department}}</td>
					<td>{{showDateTime($job_application_status_detail->created_at)}}</td>
					@if($job_application->applicant_user_id != Auth::user()->id)
						<td>
							@if($job_application_status_detail == $job_application_status_details->first() && Auth::user()->id == $job_application_status_detail->user_id)
								{!! delete_form(['job-application-status-detail.destroy',$job_application_status_detail->id],['table-refresh' => 'job-application-status-table','refresh-content' => 'load-job-application-detail']) !!}
							@endif
						</td>
					@endif
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="5">{{trans('messages.no_data_found')}}</td>
			</tr>
		@endif