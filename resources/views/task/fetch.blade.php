	@if(count($tasks))
		@foreach($tasks as $task)
		<tr>
			<td>{{$task->title}}</td>
			<td>{!! getTaskStatus($task) !!}</td>
			<td>{{$task->TaskCategory->name}}</td>
			<td>{{$task->TaskPriority->name}}</td>
			<td>{{$task->progress}} % 
				<div class="progress progress-xs" style="margin-top:0px;">
				  <div class="progress-bar progress-bar-{{progressColor($task->progress)}}" role="progressbar" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task->progress}}%">
				  </div>
				</div>
			</td>
			<td>{{showDate($task->start_date)}}</td>
			<td>{{showDate($task->due_date)}}</td>
			<td>
				<div class="btn-group btn-group-xs">
					@if($type == 'starred')
					<a href="#" data-ajax="1" data-extra="&task_id={{$task->id}}" data-source="/task-starred" class="btn btn-xs btn-default" data-table-refresh="task-starred-table"> <i class="fa fa-star starred" data-toggle="tooltip" title="{{trans('messages.remove').' '.trans('messages.favourite')}}"></i></a>
					@endif
					<a href="/task/{{$task->uuid}}" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
				</div>
			</td>
		</tr>
		@endforeach
	@else
		<tr><td colspan="8">{{trans('messages.no_data_found')}}</td></tr>
	@endif