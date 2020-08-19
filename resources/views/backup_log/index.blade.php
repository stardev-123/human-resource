@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.backup').' '.trans('messages.log') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-6 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.log') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'backup-log-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.date')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="date_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="date_end" readonly />
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
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.backup').' '.trans('messages.log') !!}</h2>
					<div class="additional-btn">
						{!! Form::open(['route' => 'backup.store','role' => 'form', 'class'=>'backup-form form-inline','id' => 'backup-form']) !!}
							<span style="font-size:12px;">{{trans('messages.delete').' '.trans('messages.old')}}</span> <input name="delete_old_backup" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1"> 
							{!! Form::submit(trans('messages.generate').' '.trans('messages.backup'),['class' => 'btn btn-primary btn-sm']) !!}
						{!! Form::close() !!}
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
					</div>
					@include('global.datatable',['table' => $table_data['backup-table']])
				</div>
			</div>
		</div>

	@stop