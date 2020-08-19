
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.holiday') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($holiday,['method' => 'PATCH','route' => ['holiday.update',$holiday] ,'class' => 'holiday-edit-form','id' => 'holiday-edit-form']) !!}
			@include('holiday._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>