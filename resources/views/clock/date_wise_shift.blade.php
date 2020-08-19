@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.date').' '.trans('messages.wise').' '.trans('messages.shift') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.shift') !!}
					<div class="additional-btn">
						@include('clock.shift_report_menu')
					</div>
					</h2>
					{!! Form::open(['url' => 'filter','id' => 'date-wise-shift-filter-form','data-no-form-clear' => 1,'class' => 'form-inline']) !!}
					  	<div class="form-group">
							{!! Form::select('user_id', $accessible_users,Auth::user()->id,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
					  	</div>
						<div class="form-group">
							<div class="input-daterange input-group">
							    <input type="text" class="input-sm form-control" name="from_date" value="{{date('Y-m-d')}}" readonly />
							    <span class="input-group-addon">{{trans('messages.to')}}</span>
							    <input type="text" class="input-sm form-control" name="to_date" value="{{date('Y-m-d')}}" readonly />
							</div>
						</div>
						<button type="submit" class="btn btn-default btn-success">{!! trans('messages.filter') !!}</button>
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.shift') !!}
					<div class="additional-btn"></div>
					</h2>
					@include('global.datatable',['table' => $table_data['date-wise-shift-table']])
				</div>
				<div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="date-wise-shift-graph"></div>
                    </div>
                </div>
			</div>
		</div>

	@stop