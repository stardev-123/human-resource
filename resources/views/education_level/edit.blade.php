	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.education').' '.trans('messages.level') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($education_level,['method' => 'PATCH','route' => ['education-level.update',$education_level] ,'class' => 'education-level-edit-form','id' => 'education-level-edit-form','data-table-refresh' => 'education-level-table']) !!}
			@include('education_level._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>