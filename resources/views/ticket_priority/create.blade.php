	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.ticket').' '.trans('messages.priority') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'ticket-priority.store','role' => 'form', 'class'=>'ticket-priority-form','id' => 'ticket-priority-form']) !!}
			@include('ticket_priority._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>