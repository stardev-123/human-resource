@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/leave">{!! trans('messages.leave') !!}</a></li>
		    <li class="active">{!! toWordTranslate('date-wise-leave-report') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong>
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'date-wise-leave-report-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-6">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.duration') !!}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{date('Y-m-d')}}" />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{date('Y-m-d')}}" />
									</div>
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.designation') !!}</label>
									{!! Form::select('designation_id[]', $designations,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>	
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.location') !!}</label>
									{!! Form::select('location_id[]', $locations,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>	
						</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.leave') !!}
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
						<a href="/leave"><button class="btn btn-sm btn-primary"><i class="fa fa-bar-chart icon"></i> {!! trans('messages.request').' '.trans('messages.leave') !!}</button></a>
						@include('leave.leave_report_menu')
					</div>
					</h2>
					@include('global.datatable',['table' => $table_data['date-wise-leave-report-table']])
				</div>
			</div>
		</div>

	@stop