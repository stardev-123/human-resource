
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_leave->User->full_name.' '.trans('messages.leave') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
                		<td>{{showDate($user_leave->from_date)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.to').' '.trans('messages.date')}}</th>
                        <td>{{showDate($user_leave->to_date)}}</td>
                    </tr>
                    @foreach($user_leave->UserLeaveDetail as $user_leave_detail)
                        <tr>
                            <th>{{ $user_leave_detail->LeaveType->name }}</th>
                            <td>{{$user_leave_detail->leave_used.'/'.$user_leave_detail->leave_assigned}}</td>
                        </tr>
                    @endforeach
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_leave->description}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_leave->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_leave->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_leave->id][$custom_field->id]) ? $values[$user_leave->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>