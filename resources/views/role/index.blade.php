@extends('layouts.app')

	@section('breadcrumb')
        <ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.role') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
                    <h2>
                        <strong>{!!trans('messages.add_new').'</strong> '.trans('messages.role')!!}
                    </h2>
                    {!! Form::open(['route' => 'role.store','role' => 'form', 'class'=>'role-form','id' => 'role-form']) !!}
						@include('role._form')
					{!! Form::close() !!}
                </div>
			</div>
			<div class="col-sm-8">
				<div class="box-info full">
                    <h2>
                        <strong>{!!trans('messages.list_all').'</strong> '.trans('messages.role')!!}
                    </h2>
					@include('global.datatable',['table' => $table_data['role-table']])
                </div>
			</div>
		</div>
	@stop