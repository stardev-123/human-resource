
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_experience->User->full_name.' '.trans('messages.experience') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.company').' '.trans('messages.name')}}</th>
                		<td>{{$user_experience->company_name}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.company').' '.trans('messages.address')}}</th>
                        <td>{{$user_experience->company_address}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.company').' '.trans('messages.contact').' '.trans('messages.number')}}</th>
                        <td>{{$user_experience->company_contact_number}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.company').' '.trans('messages.website')}}</th>
                        <td>{{$user_experience->company_website}}</td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.job').' '.trans('messages.title')}}</th>
                		<td>{{$user_experience->job_title}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.duration')}}</th>
                        <td>{{showDate($user_experience->from_date).' '.trans('messages.to').' '.showDate($user_experience->to_date)}}
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_experience->description}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.attachments')}}</th>
                        <td>
                            @foreach($uploads as $upload)
                                <p><a href="/user-experience/{{$upload->uuid}}/download"><i class="fa fa-paperclip"></i> {{$upload->user_filename}}</a></p></p>
                            @endforeach
                        </td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_experience->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_experience->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_experience->id][$custom_field->id]) ? $values[$user_experience->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>