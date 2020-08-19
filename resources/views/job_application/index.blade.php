@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.job').' '.trans('messages.application') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.job').' '.trans('messages.application') !!}</h2>
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					{!! Form::open(['route' => 'job-application.store','role' => 'form', 'class'=>'job-application-form','id' => 'job-application-form','data-file-upload' => '.file-uploader']) !!}
						@include('job_application._form')
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.job') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'job-application-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.source') !!}</label>
									{!! Form::select('source[]',['portal' => 'Portal']+config('lists.job_application_source'),'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.job').' '.trans('messages.title') !!}</label>
									{!! Form::select('job_id[]', $jobs,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="title">{!! trans('messages.email') !!}</label>
									{!! Form::input('email','email','',['class'=>'form-control','placeholder'=>trans('messages.email')])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.gender') !!}</label>
									{!! Form::select('gender[]', ['male' => trans('messages.male'),'female' => trans('messages.female')],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.status') !!}</label>
									{!! Form::select('status[]', translateList('job_application_status') ,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="date_of_closing_date_start">{{trans('messages.date_of').' '.trans('messages.birth')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="date_of_birth_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="date_of_birth_end" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="date_of_closing_date_start">{{trans('messages.date_of').' '.trans('messages.application')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="date_of_application_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="date_of_application_end" readonly />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-md-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.job').' '.trans('messages.application') !!}
					</h2>
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
						<a href="#" data-toggle="collapse" data-target="#box-detail"><button class="btn btn-sm btn-primary"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button></a>
					</div>
					@include('global.datatable',['table' => $table_data['job-application-table']])
				</div>
			</div>
		</div>

	@stop