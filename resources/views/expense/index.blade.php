@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.expense') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			@if(Entrust::can('create-expense'))
			<div class="col-sm-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.expense') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					</h2>
					{!! Form::open(['route' => 'expense.store','role' => 'form', 'class'=>'expense-form','id' => 'expense-form','data-file-upload' => '.file-uploader']) !!}
						@include('expense._form')
					{!! Form::close() !!}
				</div>
			</div>
			@endif

			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.expense') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'expense-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.head') !!}</label>
									{!! Form::select('expense_head_id[]', $expense_heads,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>	
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.user') !!}</label>
									{!! Form::select('user_id[]', $accessible_users,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>	
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.status') !!}</label>
									{!! Form::select('status[]',[
										'pending' => trans('messages.pending'),
										'rejected' => trans('messages.w_rejected'),
										'approved' => trans('messages.w_approved'),
									],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>	
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.date_of').' '.trans('messages.expense')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="date_of_expense_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="date_of_expense_end" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="created_at_date_start">{{trans('messages.created_at')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="created_at_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="created_at_end" readonly />
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
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.expense') !!}
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
						@if(Entrust::can('create-expense'))
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button>
						@endif
					</div>
					</h2>
					@include('global.datatable',['table' => $table_data['expense-table']])
				</div>
			</div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="expense-status-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="expense-head-wise-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="department-wise-expense-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="location-wise-expense-graph"></div>
                </div>
            </div>

			@if(defaultRole())
	        <div class="col-md-12">
	            <div class="box-info custom-scrollbar">
	                <div id="expense-monthly-report-graph"></div>
	            </div>
	        </div>
	        @endif
		</div>

	@stop