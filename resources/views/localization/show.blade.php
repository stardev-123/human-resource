@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/localization">{!! trans('messages.localization') !!}</a></li>
		    <li class="active">{!! config('localization.'.$locale.'.localization') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info full">
					<div class="tabs-left">	
						<ul class="nav nav-tabs col-md-2 tab-list" style="padding-right:0;">
						  <li><a href="#basic" data-toggle="tab"> {{ trans('messages.basic') }} </a></li>
						  @foreach($modules as $module)
						  <li><a href="#_{{ $module }}" data-toggle="tab"> {!! trans('messages.'.$module) !!} ({{ $word_count[$module] }})</a></li>
						  @endforeach
						</ul>
				        <div class="tab-content col-md-10 col-xs-10" style="padding:0px 25px 10px 25px;">
						  <div class="tab-pane animated fadeInRight" id="basic">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.basic') }}</strong> {{ trans('messages.configuration') }}</h2>

						    	{!! Form::model($localization,['method' => 'PATCH','route' => ['localization.plugin',$locale] ,'class' => 'localization-plugin-form','id'=>'localization-plugin-form','data-no-form-clear' => 1]) !!}
								  <div class="form-group">
								    {!! Form::label('datatable',trans('messages.table').' '.trans('messages.localization'),[])!!}
									{!! Form::select('datatable', config('locale-datatable'),isset($locale) ? config('lang.'.$locale.'.datatable') : '',['class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
								  </div>
								  <div class="form-group">
								    {!! Form::label('calendar',trans('messages.calendar').' '.trans('messages.localization'),[])!!}
									{!! Form::select('calendar', config('locale-calendar'),isset($locale) ? config('lang.'.$locale.'.calendar') : '',['class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
								  </div>
								  <div class="form-group">
								    {!! Form::label('datepicker',trans('messages.datepicker').' '.trans('messages.localization'),[])!!}
									{!! Form::select('datepicker', config('locale-datepicker'),isset($locale) ? config('lang.'.$locale.'.datepicker') : '',['class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
								  </div>
								  <div class="form-group">
								    {!! Form::label('datetimepicker',trans('messages.datetimepicker').' '.trans('messages.localization'),[])!!}
									{!! Form::select('datetimepicker', config('locale-datetimepicker'),isset($locale) ? config('lang.'.$locale.'.datetimepicker') : '',['class'=>'form-control input-xlarge show-tick','title'=>trans('messages.select_one')])!!}
								  </div>
								{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
								{!! Form::close() !!}
						    </div>
						  </div>
				          @foreach($modules as $module)
						  <div class="tab-pane animated fadeInRight" id="_{{ $module }}">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.'.$module) }}</strong> {{ trans('messages.translation') }}</h2>
						    		{!! Form::model($localization,['method' => 'PATCH','route' => ['localization.update-translation',$locale] ,'class' => 'form-horizontal','id'=>'localization_translation_'.$module, 'data-no-form-clear' => 1]) !!}
									@foreach($words as $key => $word)
										@if($word['module'] == $module)
										<div class="form-group">
									    	{!! Form::label($key,$word['value'],['class'=>'col-sm-6 control-label pull-left'])!!}
											<div class="col-sm-6">
												@if($locale == 'en')
												{!! Form::input('text',$key,(array_key_exists($key, $translation)) ? $translation[$key] : $word['value'],['class'=>'form-control','placeholder'=>trans('messages.translation')])!!}
												@else
												{!! Form::input('text',$key,(array_key_exists($key, $translation)) ? $translation[$key] : '',['class'=>'form-control','placeholder'=>trans('messages.translation')])!!}
												@endif
											</div>
									  	</div>
									  	@endif
									@endforeach
									{!! Form::hidden('module',$module) !!}
									{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
								{!! Form::close() !!}
						    </div>
						  </div>
						  @endforeach
						  <div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	@stop