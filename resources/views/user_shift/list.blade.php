
	@if($user->UserShift->count())
		@foreach($user->UserShift as $user_shift)
			<tr>
				<td>{!!($user_shift->shift_id) ? $user_shift->Shift->name : (trans('messages.custom').' ('.showTime($user_shift->in_time).' '.trans('messages.to').' '.showTime($user_shift->out_time).' '.($user_shift->overnight ? '(O)' : '').')') !!}</td>
				<td>{{showDate($user_shift->from_date)}}</td>
				<td>{{showDate($user_shift->to_date)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-shift/{{$user_shift->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-shift/{{$user_shift->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-shift.destroy',$user_shift->id],['table-refresh' => 'user-shift-table','refresh-content' => 'load-user-detail'])!!}
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