@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.task') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			@if(Entrust::can('create-task'))
			<div class="col-sm-12 collapse" id="box-detail">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.task') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div>
					</h2>
					{!! Form::open(['route' => 'task.store','role' => 'form', 'class'=>'task-form','id' => 'task-form','data-file-upload' => '.file-uploader','data-disable-enter-submission' => '1']) !!}
						@include('task._form')
					{!! Form::close() !!}
				</div>
			</div>
			@endif
			<div class="col-sm-12 collapse" id="box-detail-filter">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong> {!! trans('messages.task') !!}
					<div class="additional-btn">
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail-filter"><i class="fa fa-minus icon"></i> {!! trans('messages.hide') !!}</button>
					</div></h2>
					{!! Form::open(['url' => 'filter','id' => 'task-filter-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.category') !!}</label>
									{!! Form::select('task_category_id[]', $task_categories,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.priority') !!}</label>
									{!! Form::select('task_priority_id[]', $task_priorities,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.user') !!}</label>
									{!! Form::select('user_id[]', $users,'',['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
							  	</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="progress">{!! trans('messages.progress') !!}</label><br />
									<input name="progress" type="text" class="form-control slider" value="" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="[0,100]" data-slider-tooltip="hide" data-slider-show-value="1" />
									<span class="help-block" style="font-weight: bold;" id="slider-value">0 to 100 </span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.start').' '.trans('messages.date')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="start_date_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="start_date_end" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.due').' '.trans('messages.date')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="due_date_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="due_date_end" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="start_date_range">{{trans('messages.complete').' '.trans('messages.date')}}</label>
									<div class="input-daterange input-group">
									    <input type="text" class="input-sm form-control" name="complete_date_start" readonly />
									    <span class="input-group-addon">{{trans('messages.to')}}</span>
									    <input type="text" class="input-sm form-control" name="complete_date_end" readonly />
									</div>
								</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.type') !!}</label>
									{!! Form::select('type',['all' => trans('messages.all'),'owned' => trans('messages.owned').' by Me', 'assigned' => trans('messages.assigned').' to Me'],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
							  	</div>
							</div>
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.status') !!}</label>
									{!! Form::select('status',['' => trans('messages.select_one'), 
											'unassigned' => trans('messages.unassigned'),
											'pending' => trans('messages.pending'),
											'complete' => trans('messages.complete'),
											'overdue' => trans('messages.overdue'),
										],'',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
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
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.task') !!}
					<div class="additional-btn">
						<a href="#" data-toggle="collapse" data-target="#box-detail-filter"><button class="btn btn-sm btn-primary"><i class="fa fa-filter icon"></i> {!! trans('messages.filter') !!}</button></a>
						@if(Entrust::can('create-task'))
						<button class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#box-detail"><i class="fa fa-plus icon"></i> {!! trans('messages.add_new') !!}</button>
						@endif
						<a href="/user-task-rating" class="btn btn-sm btn-primary"><i class="fa fa-bars icon"></i> {{trans('messages.user').' '.trans('messages.task').' '.trans('messages.rating')}}</a>
					</div>
					</h2>
					@include('global.datatable',['table' => $table_data['task-table']])
				</div>
			</div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="category-wise-task-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="priority-wise-task-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="status-wise-task-graph"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box-info">
                    <div id="department-wise-task-graph"></div>
                </div>
            </div>
		</div>

	@stop