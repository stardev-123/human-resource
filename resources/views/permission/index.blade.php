@extends('layouts.app')

	@section('breadcrumb')
        <ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.permission') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
                    <h2>
                        <strong>{!!trans('messages.add_new').'</strong> '.trans('messages.permission')!!}
                    </h2>
                    {!! Form::open(['route' => 'permission.store','role' => 'form', 'class'=>'permission-form','id' => 'permission-form']) !!}
						@include('permission._form')
					{!! Form::close() !!}
                </div>
			</div>
			<div class="col-sm-8">
				<div class="box-info full">
                    <h2>
                        <strong>{!!trans('messages.list_all').'</strong> '.trans('messages.permission')!!}
                        <div class="additional-btn">
                        	<a href="/save-permission" class="btn btn-primary btn-sm">{{trans('messages.save').' '.trans('messages.permission')}}</a>
                        </div>
                    </h2>
                    @include('global.datatable',['table' => $table_data['permission-table']])
                </div>
			</div>
		</div>
	@stop