
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.location') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_location,['method' => 'PATCH','route' => ['user-location.update',$user_location->id] ,'class' => 'user-location-edit-form', 'id' => 'user-location-edit-form','data-table-refresh' => 'user-location-table','data-refresh' => 'load-user-detail']) !!}
		  	@include('user_location._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
