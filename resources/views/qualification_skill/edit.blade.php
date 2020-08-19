	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.qualification').' '.trans('messages.skill') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($qualification_skill,['method' => 'PATCH','route' => ['qualification-skill.update',$qualification_skill] ,'class' => 'qualification-skill-edit-form','id' => 'qualification-skill-edit-form','data-table-refresh' => 'qualification-skill-table']) !!}
			@include('qualification_skill._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>