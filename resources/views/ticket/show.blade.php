@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/ticket">{!! trans('messages.ticket') !!}</a></li>
		    <li class="active">{{$ticket->subject.' : '.$ticket->User->name_with_designation_and_department}}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-4">
				<div class="box-info full">
					<h2><strong>{{trans('messages.ticket').' '.trans('messages.property')}}</strong></h2>
					<div id="load-ticket-detail" data-extra="&uuid={{$ticket->uuid}}" data-source="/ticket/detail"></div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="box-info">
					<h2><strong>{{trans('messages.ticket').' '.trans('messages.description')}}</strong></h2>
					<ul class="media-list">
						<li class="media">
							<a class="pull-left" href="#">
								{!! getAvatar($ticket->user_id,40) !!}
							</a>
							<div class="media-body">
								<h4 class="media-heading"><a href="#">{!! $ticket->User->name_with_designation_and_department !!}</a> <small class="pull-right">{!! showDateTime($ticket->created_at) !!}</small>
								</h4>
								<p><strong>{{$ticket->subject}}</strong> <span class="pull-right">{!! getTicketStatus('open') !!}</span></p>
								{!! $ticket->description !!}

								@if($ticket_uploads->count())
									<br /><br />
						            @foreach($ticket_uploads as $ticket_upload)
						                <p><i class="fa fa-paperclip"></i> <a href="/ticket/{{$ticket_upload->uuid}}/download">{{$ticket_upload->user_filename}}</a></p>
						            @endforeach
						        @endif
							</div>
						</li>
					</ul>
					<div id="load-ticket-reply" data-extra="&uuid={{$ticket->uuid}}" data-source="/ticket/reply"></div>
        			<div style="margin-top:20px;">
        				<h2><strong>{{trans('messages.reply')}}</h2></strong>
        				{!! Form::model($ticket,['files'=>'true','method' => 'POST','route' => ['ticket.store-reply',$ticket->uuid] ,'class' => 'ticket-reply-form','id' => 'ticket-reply-form','data-refresh' => 'load-ticket-detail,load-ticket-reply','data-file-upload' => '.file-uploader']) !!}
        					@if($ticket->user_id != Auth::user()->id)
        					<div class="row">
        						<div class="col-md-4">
									<div class="form-group">
										{!! Form::label('status',trans('messages.status'),[])!!}
										{!! Form::select('status', [
											'open' => trans('messages.open'),
											'pending' => trans('messages.pending'),
											'close' => trans('messages.close'),
										] ,$ticket->status,['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
									</div>
        						</div>
        						<div class="col-md-4">
									<div class="form-group">
										{!! Form::label('ticket_priority_id',trans('messages.priority'),[])!!}
										{!! Form::select('ticket_priority_id', $ticket_priorities ,$ticket->ticket_priority_id,['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
									</div>
        						</div>
        						<div class="col-md-4">
									<div class="form-group">
										{!! Form::label('ticket_category_id',trans('messages.category'),[])!!}
										{!! Form::select('ticket_category_id', $ticket_categories ,$ticket->ticket_category_id,['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
									</div>
        						</div>
        					</div>
        					@endif
							<div class="form-group">
								{!! Form::textarea('description','',['class' => 'form-control summernote', 'placeholder' => trans('messages.description')])!!}
							</div>
							@include('upload.index',['module' => 'ticket-reply','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => ''])
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-paper-plane"></i> {!! trans('messages.send') !!}</button>
							</div>	
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	@stop