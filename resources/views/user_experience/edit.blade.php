
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.experience') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user_experience,['method' => 'PATCH','route' => ['user-experience.update',$user_experience->id] ,'class' => 'user-experience-edit-form', 'id' => 'user-experience-edit-form','data-table-refresh' => 'user-experience-table','data-file-upload' => '.file-uploader']) !!}
		  	@include('user_experience._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
	</div>
