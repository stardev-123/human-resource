	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.task').' '.trans('messages.priority') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'task-priority.store','role' => 'form', 'class'=>'task-priority-form','id' => 'task-priority-form']) !!}
			@include('task_priority._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>