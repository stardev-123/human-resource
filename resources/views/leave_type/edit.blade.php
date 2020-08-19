	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.leave').' '.trans('messages.type') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($leave_type,['method' => 'PATCH','route' => ['leave-type.update',$leave_type] ,'class' => 'leave-type-edit-form','id' => 'leave-type-edit-form','data-table-refresh' => 'leave-type-table']) !!}
			@include('leave_type._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>