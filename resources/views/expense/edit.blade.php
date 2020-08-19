
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.expense') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($expense,['method' => 'PATCH','route' => ['expense.update',$expense] ,'class' => 'expense-form','id' => 'expense-form-edit','data-file-upload' => '.file-uploader']) !!}
			@include('expense._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>