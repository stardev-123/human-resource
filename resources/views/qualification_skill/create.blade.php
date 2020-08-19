	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.qualification').' '.trans('messages.skill') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'qualification-skill.store','role' => 'form', 'class'=>'qualification-skill-form','id' => 'qualification-skill-form']) !!}
			@include('qualification_skill._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>