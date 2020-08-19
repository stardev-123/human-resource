
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th style="width:200px;">{{trans('messages.name')}}</th>
								<td>
									{!! ($job_application->applicant_user_id) ? ($job_application->ApplicantUser->full_name.' <span class="label label-danger">'.trans('messages.user').'</span>') : $job_application->full_name !!}
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>{{trans('messages.status')}}</th>
								<td>{!! jobApplicationStatusLable($job_application->status) !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.date_of').' '.trans('messages.application')}}</th>
								<td>{{showDate($job_application->date_of_application)}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.source')}}</th>
								<td>{{toWord($job_application->source)}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.email')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? $job_application->ApplicantUser->email : $job_application->email }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.date_of').' '.trans('messages.birth')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? showDate($job_application->ApplicantUser->Profile->date_of_birth) : showDate($job_application->date_of_birth) }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.gender')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? trans('messages.'.$job_application->ApplicantUser->Profile->gender) : trans('messages.'.$job_application->gender) }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.address')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? ($job_application->ApplicantUser->Profile->address_line_1.' '.$job_application->ApplicantUser->Profile->address_line_2) : $job_application->address_line_1.' '.$job_application->address_line_2 }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.city')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? $job_application->ApplicantUser->Profile->city : $job_application->city }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.state')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? $job_application->ApplicantUser->Profile->state : $job_application->state }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.postcode')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? $job_application->ApplicantUser->Profile->zipcode : $job_application->zipcode }}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.country')}}</th>
								<td>
									{{ ($job_application->applicant_user_id) ? config('country.'.$job_application->ApplicantUser->Profile->country_id) : config('country.'.$job_application->country_id) }}
								</td>
							</tr>
							<tr>
								<td colspan="2">
								@if($uploads->count())
									<strong>{{trans('messages.attachment')}} : </strong><br />
						            @foreach($uploads as $upload)
						                <p><i class="fa fa-paperclip"></i> <a href="/job-application/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
						            @endforeach
						        @endif
						        </td>
							</tr>
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.additional').' '.trans('messages.information')}} : </strong><br />
									{{ $job_application->additional_information }}
								</td>
							</tr>
						</tbody>
					</table>
