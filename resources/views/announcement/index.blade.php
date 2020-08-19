@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.announcement') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			@if(Entrust::can('create-announcement'))
			<div class="col-md-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.announcement') !!}</h2>
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					{!! Form::open(['route' => 'announcement.store','role' => 'form', 'class'=>'announcement-form','id' => 'announcement-form','data-file-upload' => '.file-uploader']) !!}
						@include('announcement._form')
					{!! Form::close() !!}
				</div>
			</div>
			@endif

			<div class="col-md-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.announcement') !!}
					</h2>
					<div class="additional-btn">
						@if(Entrust::can('create-announcement'))
							<a href="#" data-toggle="collapse" data-target="#box-detail"><button class="btn btn-sm btn-primary"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button></a>
						@endif
					</div>
					@include('global.datatable',['table' => $table_data['announcement-table']])
				</div>
			</div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="audience-wise-announcement-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="designation-wise-announcement-graph"></div>
                </div>
            </div>
		</div>

	@stop