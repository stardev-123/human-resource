
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.shift') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_shift,['method' => 'PATCH','route' => ['user-shift.update',$user_shift->id] ,'class' => 'user-shift-edit-form', 'id' => 'user-shift-edit-form','data-table-refresh' => 'user-shift-table']) !!}
		  	@include('user_shift._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
