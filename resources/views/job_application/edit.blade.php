
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.job').' '.trans('messages.application') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($job_application,['method' => 'PATCH','route' => ['job-application.update',$job_application] ,'class' => 'job-application-form','id' => 'job-application-form-edit','data-file-upload' => '.file-uploader']) !!}
			@include('job_application._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>