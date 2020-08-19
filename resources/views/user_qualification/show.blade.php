
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_qualification->User->full_name.' '.trans('messages.qualification') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.institute').' '.trans('messages.name')}}</th>
                		<td>{{$user_qualification->institute_name}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.education').' '.trans('messages.level')}}</th>
                		<td>{{$user_qualification->EducationLevel->name}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.qualification').' '.trans('messages.language')}}</th>
                        <td>{{$user_qualification->QualificationLanguage->name}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.qualification').' '.trans('messages.skill')}}</th>
                        <td>{{$user_qualification->QualificationSkill->name}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.duration')}}</th>
                        <td>{{showDate($user_qualification->from_date).' '.trans('messages.to').' '.showDate($user_qualification->to_date)}}
                    </tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{{$user_qualification->description}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.attachments')}}</th>
                        <td>
                            @foreach($uploads as $upload)
                                <p><a href="/user-qualification/{{$upload->uuid}}/download"><i class="fa fa-paperclip"></i> {{$upload->user_filename}}</a></p></p>
                            @endforeach
                        </td>
                    </tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($user_qualification->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_qualification->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!! isset($values[$user_qualification->id][$custom_field->id]) ? $values[$user_qualification->id][$custom_field->id] : '' !!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>