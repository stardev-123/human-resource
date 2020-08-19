	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.ticket').' '.trans('messages.category') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($ticket_category,['method' => 'PATCH','route' => ['ticket-category.update',$ticket_category] ,'class' => 'ticket-category-edit-form','id' => 'ticket-category-edit-form','data-table-refresh' => 'ticket-category-table']) !!}
			@include('ticket_category._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>