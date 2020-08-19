	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.education').' '.trans('messages.level') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'education-level.store','role' => 'form', 'class'=>'education-level-form','id' => 'education-level-form']) !!}
			@include('education_level._form')
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>