		
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>{{trans('messages.user')}}</th>
								<td>
									{{$leave->User->name_with_designation_and_department}}
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>{{trans('messages.type')}}</th>
								<td>{!! $leave->LeaveType->name !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.status')}}</th>
								<td>{!! $status !!}</td>
							</tr>
							@if($leave->status == 'approved')
								<tr>
									<th>{{trans('messages.date').' '.trans('messages.w_approved')}}</th>
									<td>
										@if(count(explode(',',$leave->date_approved)) == dateDiff($leave->from_date,$leave->to_date))
											{{trans('messages.all').' '.trans('messages.date')}}
										@else
											<ol>
												@foreach(explode(',',$leave->date_approved) as $date_approved)
													<li>{{showDate($date_approved)}}</li>
												@endforeach
											</ol>
										@endif
									</td>
								</tr>
							@endif
							<tr>
								<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
								<td>{{showDate($leave->from_date)}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.to').' '.trans('messages.date')}}</th>
								<td>{{showDate($leave->to_date)}}</td>
							</tr>
							<tr>
								<td colspan="2">
									<strong>{{trans('messages.description')}} : </strong><br />
									{{ $leave->description }}
								</td>
							</tr>
							<tr>
								<td>{{trans('messages.created_at')}} : </td>
								<td>{{ showDateTime($leave->created_at) }}</td>
							</tr>
							<tr>
								<td>{{trans('messages.updated_at')}} : </td>
								<td>{{ showDateTime($leave->updated_at) }}</td>
							</tr>
							<tr>
								<td colspan="2">
								@if($uploads->count())
									<strong>{{trans('messages.attachment')}} : </strong><br />
						            @foreach($uploads as $upload)
						                <p><i class="fa fa-paperclip"></i> <a href="/leave/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
						            @endforeach
						        @endif
						        </td>
							</tr>
						</tbody>
					</table>