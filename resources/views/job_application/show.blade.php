@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/job-application">{!! trans('messages.job').' '.trans('messages.application') !!}</a></li>
		    <li class="active">{{$job_application->Job->title}}</li>
		</ul>
	@stop
	
	@section('content')

		<div class="row">
			<div class="col-md-5">
				<div class="box-info full">
					<h2><strong>{{$job_application->Job->title}}</strong></h2>

					<div id="load-job-application-detail" data-extra="&id={{$job_application->id}}" data-source="/job-application/detail"></div>

				</div>
			</div>
			<div class="col-md-7">
				@if($job_application->applicant_user_id != Auth::user()->id)
				<div class="box-info">
					<h2><strong>{{trans('messages.status').' '.trans('messages.update')}}</strong></h2>
					{!! Form::model($job_application,['method' => 'POST','route' => ['job-application.update-status',$job_application] ,'class' => 'job-application-status-detail-form','id' => 'job-application-status-detail-form','data-table-refresh' => 'job-application-status-table','data-refresh' => 'load-job-application-detail']) !!}
						<div class="form-group">
						    {!! Form::label('status',trans('messages.status'),[])!!}
							{!! Form::select('status', $job_application_status,'',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
						</div>
						<div class="form-group">
							{!! Form::label('remarks',trans('messages.remarks'),[])!!}
							{!! Form::textarea('remarks','',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.remarks'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
							<span class="countdown"></span>
						</div>
						{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
					{!! Form::close() !!}
				</div>
				@endif

				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all').'</strong> '.trans('messages.status').' '.trans('messages.update') !!} </h2>
					<div class="table-responsive">
						<table data-sortable class="table table-hover table-striped ajax-table show-table" id="job-application-status-table" data-source="/job-application/list-status" data-extra="&id={{$job_application->id}}">
							<thead>
								<tr>
									<th>{!! trans('messages.status') !!}</th>
									<th>{!! trans('messages.remarks') !!}</th>
									<th>{!! trans('messages.user').' '.trans('messages.w_updated') !!}</th>
									<th>{!! trans('messages.created_at') !!}</th>
									@if($job_application->applicant_user_id != Auth::user()->id)
										<th data-sortable="false"></th>
									@endif
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@endsection