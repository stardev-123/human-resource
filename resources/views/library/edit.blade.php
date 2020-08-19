
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.library') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($library,['method' => 'PATCH','route' => ['library.update',$library] ,'class' => 'library-edit-form','id' => 'library-edit-form','data-file-upload' => '.file-uploader']) !!}
			@include('library._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>