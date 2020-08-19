
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.localization') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model('',['method' => 'PATCH','route' => ['localization.update',$locale] ,'class' => 'localization-edit-form','id' => 'localization-edit-form','data-form-table' => 'localization_table']) !!}
			@include('localization._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>