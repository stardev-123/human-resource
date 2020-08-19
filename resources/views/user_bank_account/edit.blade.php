
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.bank').' '.trans('messages.account') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_bank_account,['method' => 'PATCH','route' => ['user-bank-account.update',$user_bank_account->id] ,'class' => 'user-bank-account-edit-form', 'id' => 'user-bank-account-edit-form','data-table-refresh' => 'user-bank-account-table']) !!}
		  	@include('user_bank_account._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
