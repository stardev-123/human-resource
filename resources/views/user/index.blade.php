@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.user') !!}</li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			@if(Entrust::can('create-user'))
			<div class="col-md-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.user') !!}</h2>
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					{!! Form::open(['route' => 'register','role' => 'form', 'class'=>'user-form','id' => 'user-form']) !!}
						@include('user._form')
					{!! Form::close() !!}
				</div>
			</div>
			@endif

			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.user') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'user-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.role') !!}</label>
									{!! Form::select('role_id[]', $roles,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
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
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.status') !!}</label>
									{!! Form::select('status[]', [
										'active' => trans('messages.active'),
										'inactive' => trans('messages.inactive'),
										'pending_activation' => trans('messages.pending').' '.trans('messages.activation'),
										'pending_approval' => trans('messages.pending').' '.trans('messages.approval'),
										'banned' => trans('messages.banned')],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.gender') !!}</label>
									{!! Form::select('gender[]', [
										'male' => trans('messages.male'),
										'female' => trans('messages.female'),
										'other' => trans('messages.other')],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
						</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>


			<div class="col-md-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.user') !!}
					</h2>
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
						@if(Entrust::can('create-user'))
							<a href="#" data-toggle="collapse" data-target="#box-detail"><button class="btn btn-sm btn-primary"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button></a>
						@endif
						@include('user.user_report_menu')
					</div>
					@include('global.datatable',['table' => $table_data['user-table']])
				</div>
			</div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-role-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-department-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-designation-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-location-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-status-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="user-gender-wise-graph"></div>
                </div>
            </div>
		</div>

	@stop
