
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.employment') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_employment,['method' => 'PATCH','route' => ['user-employment.update',$user_employment->id] ,'class' => 'user-employment-edit-form', 'id' => 'user-employment-edit-form','data-table-refresh' => 'user-employment-table','data-refresh' => 'load-user-detail']) !!}
		  	@include('user_employment._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
