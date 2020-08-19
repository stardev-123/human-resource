	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.ticket').' '.trans('messages.category') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'ticket-category.store','role' => 'form', 'class'=>'ticket-category-form','id' => 'ticket-category-form']) !!}
			@include('ticket_category._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>