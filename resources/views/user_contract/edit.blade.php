
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.contract') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_contract,['method' => 'PATCH','route' => ['user-contract.update',$user_contract->id] ,'class' => 'user-contract-edit-form', 'id' => 'user-contract-edit-form','data-table-refresh' => 'user-contract-table','data-file-upload' => '.file-uploader']) !!}
		  	@include('user_contract._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
