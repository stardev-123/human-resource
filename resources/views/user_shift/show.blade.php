
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_shift->User->full_name.' '.trans('messages.shift') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.shift')}}</th>
                		<td>{{($user_shift->shift_id) ?  $user_shift->Shift->name : trans('messages.custom')}}</td>
                	</tr>
                    @if(!$user_shift->shift_id)
                    <tr>
                        <th>{{trans('messages.detail')}}</th>
                        <td>{{showTime($user_shift->in_time).' '.trans('messages.to').' '.showTime($user_shift->out_time).' '.($user_shift->overnight ? '(O)' : '')}}</td>
                    </tr>
                    @else
                    <tr>
                        <th>{{trans('messages.detail')}}</th>
                        <td>
                            @foreach($user_shift->Shift->ShiftDetail as $shift_detail)
                                {!! trans('messages.'.$shift_detail->day).' : '.(($shift_detail->in_time == $shift_detail->out_time) ? '' : ((($shift_detail->overnight) ? '<strong>(O)</strong>' : '').' '.showTime($shift_detail->in_time).' '.trans('messages.to').' '.showTime($shift_detail->out_time))) !!}
                                <br />
                            @endforeach
                        </td>
                    </tr>
                    @endif
                	<tr>
                		<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
                		<td>{{showDate($user_shift->from_date)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.to').' '.trans('messages.date')}}</th>
                        <td>{{showDate($user_shift->to_date)}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_shift->description}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_shift->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_shift->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_shift->id][$custom_field->id]) ? $values[$user_shift->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>
