@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.payroll') !!}</li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			<div class="col-md-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.payroll') !!}
					</h2>
					<div class="additional-btn">
					@if(Entrust::can('create-multiple-payroll'))
						<a href="/payroll/create/multiple"><button class="btn btn-sm btn-primary"><i class="fa fa-users icon"></i> {!! trans('messages.process').' '.trans('messages.payroll').' '.trans('messages.wages') !!}</button></a>
					@endif
					@if(Entrust::can('create-payroll'))
						<a href="/payroll/create"><button class="btn btn-sm btn-primary"><i class="fa fa-user icon"></i> {!! trans('messages.one-off').' '.trans('messages.payroll').' '.trans('messages.payment') !!}</button></a>
					@endif
					</div>
					@include('global.datatable',['table' => $table_data['payroll-table']])
				</div>
			</div>

			@if(defaultRole())
	        <div class="col-md-12">
	            <div class="box-info custom-scrollbar">
	                <div id="payroll-monthly-report-graph"></div>
	            </div>
	        </div>
	        @endif
		</div>

	@stop
