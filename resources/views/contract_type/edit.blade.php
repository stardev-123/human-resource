	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.contract').' '.trans('messages.type') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($contract_type,['method' => 'PATCH','route' => ['contract-type.update',$contract_type] ,'class' => 'contract-type-edit-form','id' => 'contract-type-edit-form','data-table-refresh' => 'contract-type-table']) !!}
			@include('contract_type._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>