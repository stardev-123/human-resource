	@if($task->TaskAttachment->count())
		@foreach($task->TaskAttachment as $task_attachment)
			<tr>
				<td>{{$task_attachment->title}}</td>
				<td>{{$task_attachment->description}}</td>
				<td>{{showDateTime($task_attachment->created_at)}}</td>
				<td>
					@foreach(\App\Upload::whereModule('task-attachment')->whereModuleId($task_attachment->id)->whereStatus(1)->get() as $upload)
						<p><i class="fa fa-paperclip"></i> <a href="/task-attachment/{{$upload->uuid}}/download">{{$upload->user_filename}}</a></p>
					@endforeach
				</td>
				<td>
					@if($task_attachment->user_id == Auth::user()->id)
						<div class="btn-group btn-group-xs">
							{!!delete_form(['task-attachment.destroy',$task_attachment->id],['table-refresh' => 'task-attachment-table'])!!}
						</div>
					@endif
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="5">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif