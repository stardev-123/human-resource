@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">Message</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info full">
					<div class="tabs-left">	
						<ul class="nav nav-tabs col-md-2 tab-list" style="padding-right:0;">
						  <li><a href="#compose-tab" data-toggle="tab"><i class="fa fa-pencil-square"></i> Compose</a></li>
					      <li><a href="#inbox-tab" data-toggle="tab"><i class="fa fa-inbox"></i> Inbox ({{$count_inbox}})</a></li>
						  <li><a href="#sent-tab" data-toggle="tab"><i class="fa fa-share"></i> Sent</a></li>
						  <li><a href="#starred-tab" data-toggle="tab"><i class="fa fa-star"></i> Starred</a></li>
						  <li><a href="#trash-tab" data-toggle="tab"><i class="fa fa-trash"></i> Trash</a></li>
			    		</ul>
				        <div class="tab-content col-md-10 col-xs-10" style="padding:0px 25px 10px 25px;">
						  <div class="tab-pane animated fadeInRight" id="compose-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.compose') }}</strong></h2>
                    			{!! Form::open(['files'=>'true','route' => 'message.store','role' => 'form', 'class'=>'compose-form','id' => 'compose-form','data-file-upload' => '.file-uploader']) !!}
									<div class="form-group">
										{!! Form::select('to_user_id', $users, '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
									</div>
									<div class="form-group">
										{!! Form::input('text','subject','',['class'=>'form-control','placeholder'=>trans('messages.subject')])!!}
									</div>
									<div class="form-group">
										{!! Form::textarea('body','',['class' => 'form-control summernote', 'placeholder' => trans('messages.body')])!!}
									</div>
									@include('upload.index',['module' => 'message','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => ''])
									<div class="form-group">
										<div class="pull-right">
											<button type="submit" name="submit" class="btn btn-success btn-sm"><i class="fa fa-paper-plane"></i> {!! trans('messages.send') !!}</button>
										</div>
									</div>	
								{!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="inbox-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.inbox') }}</strong></h2>
								@include('global.datatable',['table' => $table_data['inbox-table']])
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="sent-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.sent_box') }}</strong></h2>
								@include('global.datatable',['table' => $table_data['sent-table']])
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="starred-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.starred') }}</strong></h2>
								@include('global.datatable',['table' => $table_data['starred-table']])
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="trash-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.trash') }}</strong></h2>
								@include('global.datatable',['table' => $table_data['trash-table']])
						    </div>
						  </div>
						</div>
			    	</div>
			    </div>
			</div>
        </div>
    @stop