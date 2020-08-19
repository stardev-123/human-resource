@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.update').' '.trans('messages.attendance') !!}</li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
					<h2><strong>{!! trans('messages.filter') !!}</strong></h2>
					{!! Form::open(['route' => 'clock.update-attendance','role' => 'form','class'=>'update-attendance-form','id'=>'update-attendance-form','data-submit' => 'noAjax']) !!}
					  <div class="form-group">
					    {!! Form::label('user_id',trans('messages.user'),['class' => 'control-label'])!!}
					    {!! Form::select('user_id', $users, $user->id,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
					  </div>
					  <div class="form-group">
					    {!! Form::label('date',trans('messages.date'),[])!!}
						{!! Form::input('text','date',$date,['class'=>'form-control datepicker','placeholder'=>trans('messages.date'),'readonly' => 'true'])!!}
					  </div>
					  {!! Form::submit(trans('messages.get').' '.trans('messages.detail'),['class' => 'btn btn-primary']) !!}
					{!! Form::close() !!}
				</div>
			</div>

			<div class="col-sm-8">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.update').' '.trans('messages.attendance') !!}</strong></h2>
					<div style="padding:0px 15px">
					<h4>{!! $user->name_with_designation_and_department !!}</h4>
					<p><strong>{!! showDate($date).' '.$label !!}</strong></p>
					<p><strong>{{trans('messages.shift').' '.trans('messages.detail')}}:
						@if(isset($user_shift->in_time))
						   {!! showDateTime($user_shift->in_time) !!} to
						@else
						   {!! '' !!} to
						@endif
						@if(isset($user_shift->out_time))
						   {!! showDateTime($user_shift->out_time) !!}
						@else
						   {!! '' !!}
						@endif
					</strong></p>

					<div class="row">
						{!! Form::model($user,['method' => 'POST','route' => ['update-clock',$user->id,$date] ,'class' => 'clock-form','id' => 'clock-form','data-table-refresh' => 'clock-list-table']) !!}
							<div class="col-md-4">{!! Form::input('text','clock_in','',['class'=>'form-control datetimepicker','placeholder'=>trans('messages.clock_in'),'readonly' => true])!!}</div>
							<div class="col-md-4">{!! Form::input('text','clock_out','',['class'=>'form-control datetimepicker','placeholder'=>trans('messages.clock_out'),'readonly' => true])!!}</div>
							<div class="col-md-4"><button type="submit" class="btn btn-success">{!! trans('messages.add_new') !!}</button></div>
						{!! Form::close() !!}
					</div>
					</div>

					<h2><strong>{!! trans('messages.attendance').' '.trans('messages.list') !!}</strong></h2>
					<div class="table-responsive">
						<table class="table table-hover table-striped ajax-table" id="clock-list-table" data-source="/attendance/lists" data-extra="&user_id={{$user->id}}&date={{$date}}">
							<thead>
								<tr>
									<th>{!! trans('messages.in_time') !!}</th>
									<th>{!! trans('messages.out_time') !!}</th>
									<th>{!! trans('messages.option') !!}</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	@stop
