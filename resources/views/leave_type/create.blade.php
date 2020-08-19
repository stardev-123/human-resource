	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.leave').' '.trans('messages.type') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'leave-type.store','role' => 'form', 'class'=>'leave-type-form','id' => 'leave-type-form']) !!}
			@include('leave_type._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>