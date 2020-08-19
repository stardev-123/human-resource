@extends('layouts.app')

	@section('breadcrumb')
        <ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">Ip {!! trans('messages.filter') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
					<h2><strong>{!!trans('messages.add_new').'</strong> Ip '.trans('messages.filter')!!}
                    </h2>
                    {!! Form::open(['route' => 'ip-filter.store','role' => 'form', 'class'=>'ip-filter-form','id' => 'ip-filter-form']) !!}
						@include('ip_filter._form')
					{!! Form::close() !!}
                </div>
			</div>
			<div class="col-sm-8">
				<div class="box-info full">
					<h2><strong>{!!trans('messages.list_all').'</strong> Ip '.trans('messages.filter')!!}
                    </h2>
					@include('global.datatable',['table' => $table_data['ip-filter-table']])
                </div>
			</div>
		</div>
	@stop