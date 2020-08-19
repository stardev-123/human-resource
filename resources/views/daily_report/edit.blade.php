
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.daily').' '.trans('messages.report') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($daily_report,['method' => 'PATCH','route' => ['daily-report.update',$daily_report] ,'class' => 'daily-report-edit-form','id' => 'daily-report-edit-form','data-file-upload' => '.file-uploader']) !!}
			@include('daily_report._form', ['buttonText' => trans('messages.update')])
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>