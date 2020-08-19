
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.qualification') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_qualification,['method' => 'PATCH','route' => ['user-qualification.update',$user_qualification->id] ,'class' => 'user-qualification-edit-form', 'id' => 'user-qualification-edit-form','data-table-refresh' => 'user-qualification-table','data-file-upload' => '.file-uploader']) !!}
		  	@include('user_qualification._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
