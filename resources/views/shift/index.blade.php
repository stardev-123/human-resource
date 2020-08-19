@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.shift') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.shift') !!}</h2>
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					{!! Form::open(['route' => 'shift.store','role' => 'form', 'class'=>'shift-form','id' => 'shift-form']) !!}
						@include('shift._form')
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-md-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.shift') !!}
					</h2>
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail"><button class="btn btn-sm btn-primary"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button></a>
					</div>
					@include('global.datatable',['table' => $table_data['shift-table']])
				</div>
			</div>
		</div>

	@stop