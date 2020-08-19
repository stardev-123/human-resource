	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.expense').' '.trans('messages.head') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($expense_head,['method' => 'PATCH','route' => ['expense-head.update',$expense_head] ,'class' => 'expense-head-edit-form','id' => 'expense-head-edit-form','data-table-refresh' => 'expense-head-table']) !!}
			@include('expense_head._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>