
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.leave') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($leave,['method' => 'PATCH','route' => ['leave.update',$leave] ,'class' => 'leave-form','id' => 'leave-form-edit','data-file-upload' => '.file-uploader']) !!}
			@include('leave._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>