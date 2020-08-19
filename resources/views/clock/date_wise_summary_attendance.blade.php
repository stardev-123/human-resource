@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! toWordTranslate('date-wise-summary-attendance') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> </h2>
					<div class="additional-btn">
						@include('clock.attendance_report_menu')
					</div>
					{!! Form::open(['url' => 'filter','id' => 'date-wise-summary-attendance-form','data-no-form-clear' => 1]) !!}
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
						</div>
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.attendance') !!}
					<div class="additional-btn"></div>
					</h2>
					@include('global.datatable',['table' => $table_data['date-wise-summary-attendance-table']])
				</div>

                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-late-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-early-leaving-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-overtime-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-present-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-absent-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-leave-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-summary-attendance-half-day-graph"></div>
                    </div>
                </div>
			</div>
		</div>

	@stop