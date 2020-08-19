@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/payroll">{!! trans('messages.payroll') !!}</a></li>
		    <li class="active">{!! trans('messages.add_new') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
					<h2><strong>{!! trans('messages.select') !!} </strong> {!! trans('messages.option') !!}</h2>
					{!! Form::open(['route' => 'payroll.create','role' => 'form', 'class'=>'payroll-init-form','id' => 'payroll-init-form','data-submit' => 'noAjax']) !!}
					  <div class="form-group">
					    {!! Form::label('from_date',trans('messages.from').' '.trans('messages.date'),[])!!}
						{!! Form::input('text','from_date',isset($from_date) ? $from_date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.from').' '.trans('messages.date'),'readonly' => 'true'])!!}
					  </div>
					  <div class="form-group">
					    {!! Form::label('to_date',trans('messages.to').' '.trans('messages.date'),[])!!}
						{!! Form::input('text','to_date',isset($to_date) ? $to_date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.to').' '.trans('messages.date'),'readonly' => 'true'])!!}
					  </div>
					  <div class="form-group">
					    {!! Form::label('user_id',trans('messages.user'),['class' => 'control-label'])!!}
					    {!! Form::select('user_id', $users, isset($user_id) ? $user_id : '',['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
					  </div>
					  {!! Form::submit(trans('messages.get'),['class' => 'btn btn-primary pull-right','name' => 'submit']) !!}
					{!! Form::close() !!}
				</div>
				@include('payroll.summary')
			</div>

			@if(isset($user))
			<div class="col-sm-8">
				<div class="box-info">
					<h2><strong>{{trans('messages.payroll')}}</strong></h2>
					{!! Form::open(['route' => 'payroll.store','role' => 'form', 'class'=>'payroll-form','id' => 'payroll-form']) !!}
					{!! Form::hidden('user_id',$user_id)!!}
					{!! Form::hidden('from_date',$from_date)!!}
					{!! Form::hidden('to_date',$to_date)!!}
						@include('payroll._form')
					{!! Form::close() !!}	
				</div>
				@include('payroll.salary')
			</div>
			@endif
		</div>
	@stop