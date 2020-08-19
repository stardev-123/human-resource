@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.daily').' '.trans('messages.shift') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> </h2>
					<div class="additional-btn">
						@include('clock.shift_report_menu')
					</div>
					{!! Form::open(['url' => 'filter','id' => 'daily-shift-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.date') !!}</label>
									{!! Form::input('text','date',date('Y-m-d'),['class'=>'form-control datepicker','placeholder'=>trans('messages.date')])!!}
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
						</div>
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.shift') !!}
					<div class="additional-btn"></div>
					</h2>
					@include('global.datatable',['table' => $table_data['daily-shift-table']])
				</div>

                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="daily-shift-graph"></div>
                    </div>
                </div>
			</div>
		</div>

	@stop