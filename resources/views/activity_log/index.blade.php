@extends('layouts.app')

	@section('breadcrumb')
        <ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.activity').' '.trans('messages.log') !!}</li>
		</ul>
	@stop
	
	@section('content')
	
		<div class="row">
			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.log') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'activity-log-filter-form','data-no-form-clear' => 1]) !!}

						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.user') !!}</label>
									{!! Form::select('user_id',$users,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
							  	</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.date')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="date_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="date_end" readonly />
									</div>
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
                    <h2>
                        <strong>{!!trans('messages.list_all').'</strong> '.trans('messages.activity').' '.trans('messages.log')!!}
                    </h2>
					@include('global.datatable',['table' => $table_data['activity-log-table']])
                </div>
			</div>
		</div>
	@stop