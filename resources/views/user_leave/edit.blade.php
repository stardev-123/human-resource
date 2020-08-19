
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.leave') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_leave,['method' => 'PATCH','route' => ['user-leave.update',$user_leave->id] ,'class' => 'user-leave-edit-form', 'id' => 'user-leave-edit-form','data-table-refresh' => 'user-leave-table']) !!}
		  	@include('user_leave._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
