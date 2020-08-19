	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.qualification').' '.trans('messages.language') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($qualification_language,['method' => 'PATCH','route' => ['qualification-language.update',$qualification_language] ,'class' => 'qualification-language-edit-form','id' => 'qualification-language-edit-form','data-table-refresh' => 'qualification-language-table']) !!}
			@include('qualification_language._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>