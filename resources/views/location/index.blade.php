@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.location') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			@if(Entrust::can('create-location'))
			<div class="col-sm-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.location') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['route' => 'location.store','role' => 'form', 'class'=>'location-form','id' => 'location-form']) !!}
						@include('location._form')
					{!! Form::close() !!}
				</div>
			</div>
			@endif

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.location') !!}
					<div class="additional-btn">
						@if(Entrust::can('create-location'))
							<a href="#" data-toggle="collapse" data-target="#box-detail"><button class="btn btn-sm btn-primary"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button></a>
						@endif
					</div>
					</h2>
					@include('global.datatable',['table' => $table_data['location-table']])
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{{trans('messages.hierarchy')}}</strong></h2>
					<div id="location-hierarchy"></div>
				</div>
			</div>
		</div>

	@stop