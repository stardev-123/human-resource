@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/message">{!! trans('messages.message') !!}</a></li>
		    <li class="active">{{$message->subject}}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-12">
				<div class="box-info">
					<h2><strong>{{$message->subject}}</strong></h2>
        			<div id="load-message" data-uuid="{{$message->uuid}}"></div>
        			<div style="border-bottom:1px solid #f5f5f5;margin: 15px 0px;"></div>
        			<div style="margin-left:30px;margin-top:20px;">
        				<p style="font-weight: bold;font-size: 15px;">Send Reply</p>
        				{!! Form::model($message,['files'=>'true','method' => 'POST','route' => ['message.reply',$message->id] ,'class' => 'reply-form','id' => 'reply-form','data-refresh' => 'load-message','data-file-upload' => '.file-uploader']) !!}
							<div class="form-group">
								{!! Form::textarea('body','',['class' => 'form-control summernote', 'placeholder' => trans('messages.body')])!!}
							</div>
							@include('upload.index',['module' => 'message','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => ''])
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-paper-plane"></i> {!! trans('messages.send') !!}</button>
							</div>	
						{!! Form::close() !!}
					</div>
            	</div>
			</div>
		</div>
	@stop