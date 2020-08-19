		<ul class="media-list custom-scrollbar">
			@if(count($task->TaskComment))
				@foreach($task->TaskComment->sortByDesc('id') as $task_comment)
				  <li class="media">
					<a class="pull-left" href="#">
					  {!! getAvatar($task_comment->user_id,40) !!}
					</a>
					<div class="media-body">
					  <h4 class="media-heading"><a href="#">{!! $task_comment->userAdded->full_name !!}</a> <small>{!! showDateTime($task_comment->created_at) !!}</small>
					  @if(Auth::user()->id == $task_comment->user_id)
						<div class="pull-right">{!! delete_form(['task-comment.destroy',$task_comment->id],['refresh-content' => 'load-task-comment']) !!}</div>
				      @endif
				      </h4>
				      {!! $task_comment->comment !!}
					  
					</div>
				  </li>
				@endforeach
			@endif
		</ul>