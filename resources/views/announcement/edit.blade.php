
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.announcement') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($announcement,['method' => 'PATCH','route' => ['announcement.update',$announcement] ,'class' => 'announcement-edit-form','id' => 'announcement-edit-form','data-file-upload' => '.file-uploader']) !!}
			@include('announcement._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>