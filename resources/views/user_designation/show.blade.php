
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_designation->User->full_name.' '.trans('messages.designation') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.designation')}}</th>
                		<td>{{$user_designation->Designation->name}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
                		<td>{{showDate($user_designation->from_date)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.to').' '.trans('messages.date')}}</th>
                        <td>{{showDate($user_designation->to_date)}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_designation->description}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_designation->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_designation->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_designation->id][$custom_field->id]) ? $values[$user_designation->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>