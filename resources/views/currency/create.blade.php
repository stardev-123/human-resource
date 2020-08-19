
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.currency') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'currency.store','class'=>'currency-form','id' => 'currency-form','data-table-refresh' => 'currency-table']) !!}
			@include('currency._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>
