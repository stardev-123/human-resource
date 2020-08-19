
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_contract->User->full_name.' '.trans('messages.contract') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.type')}}</th>
                		<td>{{$user_contract->ContractType->name}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.title')}}</th>
                        <td>{{$user_contract->title}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.from').' '.trans('messages.date')}}</th>
                		<td>{{showDate($user_contract->from_date)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.to').' '.trans('messages.date')}}</th>
                        <td>{{showDate($user_contract->to_date)}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_contract->description}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_contract->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_contract->updated_at)}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.attachments')}}</th>
                        <td>
                            @foreach($uploads as $upload)
                                <p><a href="/user-contract/{{$upload->uuid}}/download"><i class="fa fa-paperclip"></i> {{$upload->user_filename}}</a></p></p>
                            @endforeach
                        </td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_contract->id][$custom_field->id]) ? $values[$user_contract->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>