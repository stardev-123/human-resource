
	@if($user->UserContact->count())
		@foreach($user->UserContact as $user_contact)
			<tr>
				<td>{!!$user_contact->name.' '.(($user_contact->is_primary) ? '<span class="label label-success">'.trans('messages.primary').'</span>' : '')!!}</td>
				<td>{{toWord($user_contact->relation)}}</td>
				<td>{{$user_contact->work_email}}</td>
				<td>{{$user_contact->mobile}}</td>

				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-contact/{{$user_contact->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>

					@if((Entrust::can('edit-user') && $user_contact->user_id != \Auth::user()->id) || !count(getParent()))
						@if($user_contact->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_contact->id}}" data-source="/user-contact/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-contact-table"><i class="fa fa-unlock" data-toggle="tooltip" title="{{trans('messages.unlock')}}"></i></a>
						@else(!$user_contact->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_contact->id}}" data-source="/user-contact/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-contact-table"><i class="fa fa-lock" data-toggle="tooltip" title="{{trans('messages.lock')}}"></i></a>
						@endif
					@endif

					@if(!$user_contact->is_locked && ($user_contact->user_id == \Auth::user()->id || ($user_contact->user_id != \Auth::user()->id && Entrust::can('edit-user'))))
						<a href="#" data-href="/user-contact/{{$user_contact->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-contact.destroy',$user_contact->id],['table-refresh' => 'user-contact-table','refresh-content' => 'load-user-detail'])!!}
					@endif
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="5">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif