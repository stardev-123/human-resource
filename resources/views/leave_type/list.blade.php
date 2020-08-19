
	@if($leave_types->count())
		@foreach($leave_types as $leave_type)
			<tr>
				<td>
					{{$leave_type->name}}
					@if($leave_type->is_half_day)
						<span class="label label-primary">{{trans('messages.half').' '.trans('messages.day')}}</span>
					@endif
				</td>
				<td>{{$leave_type->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/leave-type/{{$leave_type->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['leave-type.destroy',$leave_type->id],['table-refresh' => 'leave-type-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif