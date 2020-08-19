
	@if($user->UserDesignation->count())
		@foreach($user->UserDesignation as $user_designation)
			<tr>
				<td>{!!$user_designation->Designation->name !!}</td>
				<td>{{showDate($user_designation->from_date)}}</td>
				<td>{{showDate($user_designation->to_date)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-designation/{{$user_designation->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-designation/{{$user_designation->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-designation.destroy',$user_designation->id],['table-refresh' => 'user-designation-table','refresh-content' => 'load-user-detail'])!!}
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