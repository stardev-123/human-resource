
	@if($user->UserExperience->count())
		@foreach($user->UserExperience as $user_experience)
			<tr>
				<td>{!!$user_experience->company_name !!}</td>
				<td>{{showDate($user_experience->from_date).' '.trans('messages.to').' '.showDate($user_experience->to_date)}}</td>
				<td>{{$user_experience->job_title}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-experience/{{$user_experience->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>

					@if((Entrust::can('edit-user') && $user_experience->user_id != \Auth::user()->id) || !count(getParent()))
						@if($user_experience->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_experience->id}}" data-source="/user-experience/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-experience-table"><i class="fa fa-unlock" data-toggle="tooltip" title="{{trans('messages.unlock')}}"></i></a>
						@else(!$user_experience->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_experience->id}}" data-source="/user-experience/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-experience-table"><i class="fa fa-lock" data-toggle="tooltip" title="{{trans('messages.lock')}}"></i></a>
						@endif
					@endif

					@if(!$user_experience->is_locked && ($user_experience->user_id == \Auth::user()->id || ($user_experience->user_id != \Auth::user()->id && Entrust::can('edit-user'))))
						<a href="#" data-href="/user-experience/{{$user_experience->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-experience.destroy',$user_experience->id],['table-refresh' => 'user-experience-table','refresh-content' => 'load-user-detail'])!!}
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