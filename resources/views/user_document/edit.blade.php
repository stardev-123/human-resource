
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.document') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_document,['method' => 'PATCH','route' => ['user-document.update',$user_document->id] ,'class' => 'user-document-edit-form', 'id' => 'user-document-edit-form','data-table-refresh' => 'user-document-table','data-file-upload' => '.file-uploader']) !!}
		  	@include('user_document._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
