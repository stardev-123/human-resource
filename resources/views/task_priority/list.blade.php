
	@if($task_priorities->count())
		@foreach($task_priorities as $task_priority)
			<tr>
				<td>{{$task_priority->name}}</td>
				<td>{{$task_priority->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/task-priority/{{$task_priority->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['task-priority.destroy',$task_priority->id],['table-refresh' => 'task-priority-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif