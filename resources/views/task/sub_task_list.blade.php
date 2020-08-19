	@if($task->SubTask->count())
		@foreach($task->SubTask as $sub_task)
		<tr>
			<td>{{$sub_task->title}}</td>
			<td>{{$sub_task->description}}</td>
			<td>{{$sub_task->UserAdded->full_name}}</td>
			<td>{{showDate($sub_task->created_at)}}</td>
			<td>
				<div class="btn-group btn-group-xs">
					<a href="#" data-href="/sub-task/{{$sub_task->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!! delete_form(['sub-task.destroy',$sub_task->id],['table-refresh' => 'sub-task-table'])!!}
				</div>
			</td>
		</tr>
		@endforeach
	@else
		<tr>
			<td colspan="5">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif