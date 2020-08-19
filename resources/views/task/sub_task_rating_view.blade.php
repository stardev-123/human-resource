
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.view').' '.trans('messages.sub').' '.trans('messages.task').' '.trans('messages.rating') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>{{trans('messages.sub').' '.trans('messages.task')}}</th>
						<th>{{trans('messages.rating')}}</th>
						<th>{{trans('messages.comment')}}</th>
					</tr>
				</thead>
				<tbody>
				@foreach($task->SubTask as $sub_task)
					<tr>
						<td>{{$sub_task->title}}</td>
						<td>{!! ($sub_task->SubTaskRating->where('user_id',$user->id)->count()) ? getRatingStar($sub_task->SubTaskRating->where('user_id',$user->id)->first()->rating) : '' !!}</td>
						<td>{{($sub_task->SubTaskRating->where('user_id',$user->id)->count()) ? $sub_task->SubTaskRating->where('user_id',$user->id)->first()->comment : ''}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>