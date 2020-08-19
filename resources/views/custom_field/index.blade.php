@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.custom').' '.trans('messages.field') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-4">
				<div class="box-info">
					<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.custom').' '.trans('messages.field') !!}
					</h2>
					{!! Form::open(['route' => 'custom-field.store','role' => 'form', 'class'=>'custom-field-form','id' => 'custom-field-form','data-disable-enter-submission' => '1']) !!}
					  <div class="form-group">
					    {!! Form::label('form',trans('messages.form'),[])!!}
						{!! Form::select('form', $forms,'',['class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
					  </div>
					  <div class="form-group">
					    {!! Form::label('title',trans('messages.title'),[])!!}
						{!! Form::input('text','title','',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
					  </div>
					  <div class="form-group">
		                <div class="checkbox">
		                <input name="is_required" type="checkbox" class="switch-input" data-size="mini" data-on-text="Required" data-off-text="Optional" value="1" checked>
		                </div>
		              </div>
					  <div class="form-group">
					    {!! Form::label('type',trans('messages.type'),[])!!}
						{!! Form::select('type', [
							'text' => 'Text Box',
							'number' => 'Number',
							'email' => 'Email',
							'url' => 'URL',
							'date' => 'Date',
							'select' => 'Select Box',
							'radio' => 'Radio Button',
							'checkbox' => 'Check Box',
							'textarea' => 'Textarea'
							],'',['id' => 'type', 'class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
					  </div>
					  <div class="custom-field-option">
						<div class="form-group">
						    {!! Form::label('options',trans('messages.option'),[]) !!}
							{!! Form::input('text','options','',['class'=>'form-control','placeholder'=>trans('messages.option'),'data-role' => 'tagsinput']) !!}
						</div>
					  </div>
					  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
	
					{!! Form::close() !!}
				</div>
			</div>
			<div class="col-sm-8">
				<div class="box-info full">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.custom').' '.trans('messages.field') !!}
					</h2>
					@include('global.datatable',['table' => $table_data['custom-field-table']])
				</div>
			</div>
		</div>
	@stop