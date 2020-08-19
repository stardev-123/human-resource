	@foreach($task->User as $user)
		<?php 
			$rating = getSubTaskRating($task->id,$user->id,1); 
		?>
		<tr>
			<td>{{ $user->full_name }}</td>
			<td>{!! getRatingStar($rating) !!}</td>
			<td>
				<div class="btn-group btn-group-xs">
					@if($rating)
                    	<a href="#" data-href="/sub-task-rating/{{$task->id}}/{{$user->id}}/show" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view').' '.trans('messages.rating')}}"></i></a>
                    @endif
					@if($task->user_id == Auth::user()->id)
                    	<a href="#" data-href="/sub-task-rating/{{$task->id}}/{{$user->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal">
                    		@if(!$rating)
                    		<i class="fa fa-plus" data-toggle="tooltip" title="{{trans('messages.add').' '.trans('messages.rating')}}"></i>
                    		@else
                    		<i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit').' '.trans('messages.rating')}}"></i>
                    		@endif
                    	</a>
                    	@if($rating)
							<a href="#" data-ajax="1" data-extra="&task_id={{$task->id}}&user_id={{$user->id}}" data-source="/sub-task-rating-destroy" class="btn btn-xs btn-danger" data-table-refresh="sub-task-rating-table" data-refresh="load-task-activity"> <i class="fa fa-trash" data-toggle="tooltip" title="{{trans('messages.delete').' '.trans('messages.rating')}}" ></i></a>
						@endif
					@endif
                </div>
			</td>
		</tr>
	@endforeach