@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.holiday') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-4">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.holiday') !!}</h2>
					{!! Form::open(['route' => 'holiday.store','role' => 'form', 'class'=>'holiday-form','id' => 'holiday-form']) !!}
						@include('holiday._form')
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-md-8">
				<div class="collapse" id="box-detail-filter">
					<div class="box-info">
						<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.holiday') !!}
						<div class="additional-btn">
							<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
						</div></h2>
						{!! Form::open(['url' => 'filter','id' => 'holiday-filter-form','data-no-form-clear' => 1]) !!}
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										{!! Form::label('month',trans('messages.month'),[])!!}
										{!! Form::select('month[]',$months,'',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										{!! Form::label('year',trans('messages.year'),[])!!}
										{!! Form::select('year',$years,date('Y'),['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
									</div>
								</div>
							</div>
							<div class="form-group">
							<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
							</div>
						{!! Form::close() !!}
					</div>
				</div>
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.holiday') !!}
					</h2>
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
					</div>
					@include('global.datatable',['table' => $table_data['holiday-table']])
				</div>
			</div>
		</div>

		<div class="row">
            <div class="col-md-12">
                <div class="box-info">
                	<div class="custom-scrollbar">
                    	<div id="holiday-graph"></div>
                    </div>
                </div>
            </div>
		</div>

	@stop