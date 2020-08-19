@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/task">{!! trans('messages.task') !!}</a></li>
		    <li class="active">{!! trans('messages.user').' '.trans('messages.task').' '.trans('messages.summary') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong>
					</h2>
					{!! Form::open(['url' => 'filter','id' => 'user-task-summary-form','data-no-form-clear' => 1]) !!}
						<div class="row">
							<div class="col-md-3">
							  	<div class="form-group">
									<label for="to_date">{!! trans('messages.user') !!}</label>
									{!! Form::select('user_id', $users,Auth::user()->id,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
							  	</div>
							</div>
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
									<label for="progress">{!! trans('messages.progress') !!}</label><br />
									<input name="progress" type="text" class="form-control slider" value="" data-slider-min="0" data-slider-max="100" data-slider-step="1" data-slider-value="[0,100]" data-slider-tooltip="hide" data-slider-show-value="1" />
									<span class="help-block" style="font-weight: bold;" id="slider-value">0% to 100%</span>
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
						</div>
						<div class="form-group">
						<button type="submit" class="btn btn-default btn-success pull-right">{!! trans('messages.filter') !!}</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.user').' '.trans('messages.task').' '.trans('messages.summary') !!}
					</h2>
					@include('global.datatable',['table' => $table_data['user-task-summary-table']])
				</div>
			</div>
		</div>

	@stop