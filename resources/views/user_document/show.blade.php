
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_document->User->full_name.' '.trans('messages.document') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.title')}}</th>
                		<td>{{$user_document->title}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.type')}}</th>
                		<td>{{$user_document->DocumentType->name}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.date_of').' '.trans('messages.expiry')}}</th>
                        <td>{{showDate($user_document->date_of_expiry)}}
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_document->description}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.attachments')}}</th>
                        <td>
                            @foreach($uploads as $upload)
                                <p><a href="/user-document/{{$upload->uuid}}/download"><i class="fa fa-paperclip"></i> {{$upload->user_filename}}</a></p></p>
                            @endforeach
                        </td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_document->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_document->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!!isset($values[$user_document->id][$custom_field->id]) ? $values[$user_document->id][$custom_field->id] : ''!!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>