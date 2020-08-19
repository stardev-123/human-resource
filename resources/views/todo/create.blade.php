
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.to_do') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'todo.store','role' => 'form', 'class'=>'todo-form','id' => 'todo-form']) !!}
			@include('todo._form')
		{!! Form::close() !!}
	</div>