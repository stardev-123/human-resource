@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.date').' '.trans('messages.wise').' '.trans('messages.attendance') !!}</li>
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
					{!! Form::open(['url' => 'filter','id' => 'date-wise-attendance-form','data-no-form-clear' => 1,'class'=>'form-inline']) !!}
						<div class="input-daterange input-group">
						    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{date('Y-m-d')}}" />
						    <span class="input-group-addon">{{trans('messages.to')}}</span>
						    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{date('Y-m-d')}}" />
						</div>
						<div class="form-group">
							{!! Form::select('user_id', $accessible_users,Auth::user()->id,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
					  	</div>
						<button type="submit" class="btn btn-default btn-success">{!! trans('messages.filter') !!}</button>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.date').' '.trans('messages.wise').' '.trans('messages.attendance') !!}
					<div class="additional-btn"></div>
					</h2>
					@include('global.datatable',['table' => $table_data['date-wise-attendance-table']])
				</div>

                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-attendance-late-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-attendance-early-leaving-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-attendance-overtime-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-attendance-working-graph"></div>
                    </div>
                </div>
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-attendance-rest-graph"></div>
                    </div>
                </div>
			</div>
		</div>

	@stop