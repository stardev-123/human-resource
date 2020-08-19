
	@if($user->UserEmployment->count())
		@foreach($user->UserEmployment as $user_employment)
			<tr>
				<td>{{showDate($user_employment->date_of_joining)}}</td>
				<td>{{showDate($user_employment->date_of_leaving)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-employment/{{$user_employment->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-employment/{{$user_employment->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-employment.destroy',$user_employment->id],['table-refresh' => 'user-employment-table','refresh-content' => 'load-user-detail'])!!}
					@endif
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif