	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.location') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($location,['method' => 'PATCH','route' => ['location.update',$location] ,'class' => 'location-edit-form','id' => 'location-edit-form']) !!}
			@include('location._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>