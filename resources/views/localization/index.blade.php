@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.localization') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.word') !!}</h2>
					{!! Form::open(['route' => 'localization.add-words','role' => 'form', 'class'=>'translation-entry-form','id' => 'translation-entry-form']) !!}
			  		  <div class="form-group">
					    {!! Form::label('text','Key',[])!!}
						{!! Form::input('text','key','',['class'=>'form-control','placeholder'=>'Key'])!!}
					  </div>
			  		  <div class="form-group">
					    {!! Form::label('text',trans('messages.word_or_sentence'),[])!!}
						{!! Form::input('text','text','',['class'=>'form-control','placeholder'=>trans('messages.word_or_sentence')])!!}
					  </div>
			  		  <div class="form-group">
					    {!! Form::label('module',trans('messages.module'),[])!!}
						{!! Form::input('text','module','',['class'=>'form-control','placeholder'=>trans('messages.module')])!!}
					  </div>
					{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
					{!! Form::close() !!}
				</div>
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.localization') !!}</h2>
					{!! Form::open(['route' => 'localization.store','role' => 'form', 'class'=>'localization-form','id' => 'localization-form','data-form-table' => 'localization_table']) !!}
						@include('localization._form')
					{!! Form::close() !!}
				</div>
			</div>
			<div class="col-sm-8">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.localization') !!}</h2>
					@include('global.datatable',['table' => $table_data['localization-table']])
				</div>
			</div>

		</div>

	@stop