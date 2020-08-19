
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.permission') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($permission,['method' => 'PATCH','route' => ['permission.update',$permission->id] ,'class' => 'permission-form','id' => 'permission-form-edit']) !!}
			@include('permission._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clearfix"></div>
	</div>