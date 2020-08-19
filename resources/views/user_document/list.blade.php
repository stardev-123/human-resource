
	@if($user->UserDocument->count())
		@foreach($user->UserDocument as $user_document)
			<tr>
				<td>{!!$user_document->DocumentType->name !!}</td>
				<td>{{$user_document->title}}</td>
				<td>{{showDate($user_document->date_of_expiry)}}</td>
				<td>{{showDateTime($user_document->updated_at)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-document/{{$user_document->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>

					@if((Entrust::can('edit-user') && $user_document->user_id != \Auth::user()->id) || !count(getParent()))
						@if($user_document->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_document->id}}" data-source="/user-document/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-document-table"><i class="fa fa-unlock" data-toggle="tooltip" title="{{trans('messages.unlock')}}"></i></a>
						@else(!$user_document->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_document->id}}" data-source="/user-document/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-document-table"><i class="fa fa-lock" data-toggle="tooltip" title="{{trans('messages.lock')}}"></i></a>
						@endif
					@endif

					@if(!$user_document->is_locked && ($user_document->user_id == \Auth::user()->id || ($user_document->user_id != \Auth::user()->id && Entrust::can('edit-user'))))
						<a href="#" data-href="/user-document/{{$user_document->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-document.destroy',$user_document->id],['table-refresh' => 'user-document-table','refresh-content' => 'load-user-detail'])!!}
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