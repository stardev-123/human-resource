	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.qualification').' '.trans('messages.language') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'qualification-language.store','role' => 'form', 'class'=>'qualification-language-form','id' => 'qualification-language-form']) !!}
			@include('qualification_language._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>