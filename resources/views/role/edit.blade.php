
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.role') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($role,['method' => 'PATCH','route' => ['role.update',$role->id] ,'class' => 'role-form','id' => 'role-form-edit']) !!}
			@include('role._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clearfix"></div>
	</div>