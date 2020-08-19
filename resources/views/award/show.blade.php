
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.award').' '.trans('messages.detail') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.category')}}</th>
                		<td>{{$award->AwardCategory->name}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.date_of').' '.trans('messages.show')}}</th>
                		<td>{{showDate($award->date_of_award)}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.duration')}}</th>
                		<td>
                			{{$award_duration}}
                		</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.user')}}</th>
                		<td>
                			<ol>
                				@foreach($award->User as $user)
                					<li>{{$user->name_with_designation_and_department}}</li>
                				@endforeach
                			</ol>
                		</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.description')}}</th>
                		<td>{!! $award->description !!}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.user').' '.trans('messages.w_added')}}</th>
                		<td>{{$award->UserAdded->name_with_designation_and_department}}</td>
                	</tr>
                	<tr>
                		<th>{{trans('messages.created_at')}}</th>
                		<td>{{showDateTime($award->created_at)}}</td>
                	</tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($award->updated_at)}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.attachments')}}</th>
                        <td>
                            @foreach($uploads as $upload)
                                <p><a href="/award/{{$upload->uuid}}/download"><i class="fa fa-paperclip"></i> {{$upload->user_filename}}</a></p></p>
                            @endforeach
                        </td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</td>
                                <td>{!! isset($values[$award->id][$custom_field->id]) ? $values[$award->id][$custom_field->id] : ''!!}</th>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
		<div class="clear"></div>
	</div>