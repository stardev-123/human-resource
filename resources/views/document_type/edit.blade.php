	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.document').' '.trans('messages.type') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($document_type,['method' => 'PATCH','route' => ['document-type.update',$document_type] ,'class' => 'document-type-edit-form','id' => 'document-type-edit-form','data-table-refresh' => 'document-type-table']) !!}
			@include('document_type._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>