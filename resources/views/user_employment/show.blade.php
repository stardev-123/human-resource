
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_employment->User->full_name.' '.trans('messages.employment') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.date_of').' '.trans('messages.joining')}}</th>
                		<td>{{showDate($user_employment->date_of_joining)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.date_of').' '.trans('messages.leaving')}}</th>
                        <td>{{showDate($user_employment->date_of_leaving)}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.leaving').' '.trans('messages.remarks')}}</th>
                		<td>{{$user_employment->leaving_remarks}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_employment->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_employment->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_employment->id][$custom_field->id]) ? $values[$user_employment->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>