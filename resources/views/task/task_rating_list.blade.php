	@foreach($task->User as $user)
		<tr>
			<td>{{ $user->full_name }}</td>
			<td>{!! $user->pivot->rating ? getRatingStar($user->pivot->rating) : '' !!}
			<td>{{ $user->pivot->rating ? $user->pivot->comment : '' }}</td>
			<td>{{ $user->pivot->rating ? showDateTime($user->pivot->updated_at) : '' }}</td>
			@if($task->user_id == Auth::user()->id)
				<td>
					<div class="btn-group btn-group-xs">
						<a href="#" data-href="/task-rating/{{$task->id}}/{{$user->id}}" data-toggle="modal" data-target="#myModal" class="btn btn-xs btn-default">
						@if($user->pivot->rating)
						<i class="fa fa-edit icon" data-toggle="tooltip" title="{{trans('messages.edit').' '.trans('messages.rating')}}"></i>
						@else
						<i class="fa fa-plus icon" data-toggle="tooltip" title="{{trans('messages.add').' '.trans('messages.rating')}}"></i>
						@endif
						</a>

						@if($user->pivot->rating)
						<a href="#" data-ajax="1" data-extra="&task_id={{$task->id}}&user_id={{$user->id}}" data-source="/task-rating-destroy" class="btn btn-xs btn-danger" data-table-refresh="task-rating-table" data-refresh="load-task-activity"> <i class="fa fa-trash" data-toggle="tooltip" title="{{trans('messages.delete').' '.trans('messages.rating')}}" ></i></a>
						@endif
                    </div>
				</td>
			@endif
		</tr>
	@endforeach