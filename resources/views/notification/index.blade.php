@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.notification') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-3">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.notification') !!}</h2>
					{!! Form::open(['url' => 'filter','id' => 'notification-filter-form','data-no-form-clear' => 1]) !!}
					  	<div class="form-group">
							<label for="to_date">{!! trans('messages.category') !!}</label>
							{!! Form::select('status',['read' => trans('messages.read'),'unread' => trans('messages.unread'),'all' => trans('messages.all')],'unread',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
					  	</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-md-9">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.notification') !!}
					</h2>
					@include('global.datatable',['table' => $table_data['notification-table']])
				</div>
			</div>
		</div>

	@stop