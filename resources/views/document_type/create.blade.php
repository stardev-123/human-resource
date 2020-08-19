	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.document').' '.trans('messages.type') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'document-type.store','role' => 'form', 'class'=>'document-type-form','id' => 'document-type-form']) !!}
			@include('document_type._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>