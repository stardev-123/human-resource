
	@if($user->UserQualification->count())
		@foreach($user->UserQualification as $user_qualification)
			<tr>
				<td>{!!$user_qualification->institute_name !!}</td>
				<td>{{showDate($user_qualification->from_date).' '.trans('messages.to').' '.showDate($user_qualification->to_date)}}</td>
				<td>{{$user_qualification->EducationLevel->name}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-qualification/{{$user_qualification->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>

					@if((Entrust::can('edit-user') && $user_qualification->user_id != \Auth::user()->id) || !count(getParent()))
						@if($user_qualification->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_qualification->id}}" data-source="/user-qualification/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-qualification-table"><i class="fa fa-unlock" data-toggle="tooltip" title="{{trans('messages.unlock')}}"></i></a>
						@else(!$user_qualification->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_qualification->id}}" data-source="/user-qualification/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-qualification-table"><i class="fa fa-lock" data-toggle="tooltip" title="{{trans('messages.lock')}}"></i></a>
						@endif
					@endif

					@if(!$user_qualification->is_locked && ($user_qualification->user_id == \Auth::user()->id || ($user_qualification->user_id != \Auth::user()->id && Entrust::can('edit-user'))))
						<a href="#" data-href="/user-qualification/{{$user_qualification->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-qualification.destroy',$user_qualification->id],['table-refresh' => 'user-qualification-table','refresh-content' => 'load-user-detail'])!!}
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