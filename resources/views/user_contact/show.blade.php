
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_contact->User->full_name.' '.trans('messages.contact') !!}</h4>
	</div>
	<div class="modal-body">
		<h4>{{ trans('messages.name').': '.$user_contact->name }}</h4>
		{!! ($user_contact->is_primary) ? '<span class="label label-success">'.trans('messages.primary').'</span>' : '' !!}
		{!! ($user_contact->is_dependent) ? '<span class="label label-danger">'.trans('messages.dependent').'</span>' : '' !!}
		<div class="row">
			<div class="col-md-6">
				<address>
				  <i class="fa fa-map-marker icon"></i> <strong>{{ $user_contact->address_line_1 }}</strong><br>
				  {{ $user_contact->address_line_2 }}<br>
				  {{ $user_contact->city.', '.$user_contact->state.', '.$user_contact->zipcode.', '.$user_contact->country_id }}<br><br />
				  <i class="fa fa-phone-square icon"></i> {{ trans('messages.work').': '.$user_contact->work_phone.' '.trans('messages.ext').': '.$user_contact->work_phone_ext }} <br />
				  <i class="fa fa-mobile icon"></i> {{ trans('messages.mobile').' | '. $user_contact->mobile.' | '. trans('messages.home').': '.$user_contact->home }} <br /><br />
				  <i class="fa fa-envelope icon"></i> {{ trans('messages.work').': '.$user_contact->work_email.' | '.trans('messages.personal').': '.$user_contact->personal_email }}
				</address>
			</div>
			<div class="col-md-6">

				@if(config('config.enable_custom_field') && count($custom_fields))
					<div class="table-responsive">
                        <table data-sortable class="table table-hover table-striped table-bordered">
                            <tbody>
			                	<tr>
			                		<th>{{trans('messages.created_at')}}</th>
			                		<td>{{showDateTime($user_contact->created_at)}}</td>
			                	</tr>
			                    <tr>
			                        <th>{{trans('messages.updated_at')}}</th>
			                        <td>{{showDateTime($user_contact->updated_at)}}</td>
			                    </tr>
                            	@foreach($custom_fields as $custom_field)
                                    <tr>
	                                    <th>{{$custom_field->title}}</th>
	                                    <td>{!!isset($values[$user_contact->id][$custom_field->id]) ? $values[$user_contact->id][$custom_field->id] : ''!!}</td>
	                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
				@endif
				
			</div>
		</div>
	</div>