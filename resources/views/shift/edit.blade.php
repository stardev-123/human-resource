
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.shift') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($shift,['method' => 'PATCH','route' => ['shift.update',$shift] ,'class' => 'shift-edit-form','id' => 'shift-edit-form']) !!}
			@include('shift._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>