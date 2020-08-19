		
					<table class="table table-hover table-striped show-table">
						<thead>
							<tr>
								<th>{{trans('messages.number')}}</th>
								<td>#{!! str_pad($ticket->id, 4, '0', STR_PAD_LEFT) !!}</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>{{trans('messages.user')}}</th>
								<td>
									{{$ticket->User->full_name}}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.designation')}}</th>
								<td>
									{{$ticket->User->designation_name}}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.department')}}</th>
								<td>
									{{$ticket->User->department_name}}
								</td>
							</tr>
							<tr>
								<th>{{trans('messages.category')}}</th>
								<td>{!! $ticket->TicketCategory->name !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.priority')}}</th>
								<td>{!! $ticket->TicketPriority->name !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.status')}}</th>
								<td>{!! getTicketStatus($ticket->status) !!}</td>
							</tr>
							<tr>
								<th>{{trans('messages.created_at')}}</th>
								<td>{{showDateTime($ticket->created_at)}}</td>
							</tr>
							<tr>
								<th>{{trans('messages.updated_at')}}</th>
								<td>{{showDateTime($ticket->updated_at)}}</td>
							</tr>
							@if(config('config.enable_custom_field'))
			                	@foreach($custom_fields as $custom_field)
			                        <tr>
			                            <th>{{$custom_field->title}}</th>
			                            <td>{{isset($values[$ticket->id][$custom_field->id]) ? $values[$ticket->id][$custom_field->id] : ''}}</td>
			                        </tr>
			                    @endforeach
			                @endif
						</tbody>
					</table>