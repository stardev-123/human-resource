
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.currency') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($currency,['method' => 'PATCH','route' => ['currency.update',$currency->id] ,'class' => 'currency-edit-form','id' => 'currency-edit-form','data-table-refresh' => 'currency-table']) !!}
			@include('currency._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>
