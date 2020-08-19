
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.salary') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_salary,['method' => 'PATCH','route' => ['user-salary.update',$user_salary->id] ,'class' => 'user-salary-edit-form', 'id' => 'user-salary-edit-form','data-table-refresh' => 'user-salary-table']) !!}
		  	@include('user_salary._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
