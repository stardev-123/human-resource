	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.task').' '.trans('messages.category') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'task-category.store','role' => 'form', 'class'=>'task-category-form','id' => 'task-category-form']) !!}
			@include('task_category._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>