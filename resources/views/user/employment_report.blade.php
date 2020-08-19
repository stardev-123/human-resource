@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.employment').' '.trans('messages.report') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> </h2>
					<div class="additional-btn">
						@include('user.user_report_menu')
					</div>
					{!! Form::open(['url' => 'filter','id' => 'employment-report-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-6">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.duration') !!}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{date('Y-m-d')}}" />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{date('Y-m-d')}}" />
									</div>
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.type') !!}</label>
									{!! Form::select('type', [
										'new' => trans('messages.new').' '.trans('messages.employment'),
										'end' => trans('messages.end').' '.trans('messages.employment')],'new',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
							  	</div>
							</div>
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
										'female' => trans('messages.female')],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
						</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.employment') !!}</strong> {!! trans('messages.report') !!}
					<div class="additional-btn"></div>
					</h2>
					@include('global.datatable',['table' => $table_data['employment-report-table']])
				</div>
			</div>

			@if(defaultRole())
	        <div class="col-md-12">
	            <div class="box-info custom-scrollbar">
	                <div id="employment-report-graph"></div>
	            </div>
	            <div class="box-info custom-scrollbar">
	                <div id="salary-wise-employment-report-graph"></div>
	            </div>
	            <div class="box-info custom-scrollbar">
	                <div id="monthly-salary-wise-employment-report-graph"></div>
	            </div>
	        </div>
	        @endif
		</div>

	@stop