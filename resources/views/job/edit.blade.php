
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.job') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($job,['method' => 'PATCH','route' => ['job.update',$job] ,'class' => 'job-form','id' => 'job-form-edit','data-file-upload' => '.file-uploader']) !!}
			@include('job._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>