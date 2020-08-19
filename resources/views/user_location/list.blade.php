
	@if($user->UserLocation->count())
		@foreach($user->UserLocation as $user_location)
			<tr>
				<td>{!!$user_location->Location->name !!}</td>
				<td>{{showDate($user_location->from_date)}}</td>
				<td>{{showDate($user_location->to_date)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-location/{{$user_location->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-location/{{$user_location->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-location.destroy',$user_location->id],['table-refresh' => 'user-location-table','refresh-content' => 'load-user-detail'])!!}
					@endif
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="4">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif