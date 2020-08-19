@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li><a href="/task">{!! trans('messages.task') !!}</a></li>
		    <li class="active">{{$task->title}}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-md-8">
				<div class="box-info full">
					<ul class="nav nav-tabs nav-justified">
					  <li class="active"><a href="#detail-tab" data-toggle="tab"><i class="fa fa-home"></i> {!! trans('messages.detail') !!}</a></li>
					  <li><a href="#sub-task-tab" data-toggle="tab"><i class="fa fa-tasks"></i> {!! trans('messages.sub').' '.trans('messages.task') !!}</a></li>
					  <li><a href="#comment-tab" data-toggle="tab"><i class="fa fa-comment"></i> {!! trans('messages.comment') !!}</a></li>
					  <li><a href="#note-tab" data-toggle="tab"><i class="fa fa-pencil"></i> {!! trans('messages.note') !!}</a></li>
					  <li><a href="#attachment-tab" data-toggle="tab"><i class="fa fa-paperclip"></i> {!! trans('messages.attachment') !!}</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane animated active fadeInRight" id="detail-tab">
							<div class="user-profile-content">
								
								<div id="load-task-description" data-extra="&id={{$task->id}}" data-source="/task-description"></div>

				                <h2><strong>{{trans('messages.task')}}</strong> {{trans('messages.progress')}}</h2>
				                
				                {!! Form::model($task,['method' => 'POST','route' => ['task.progress',$task] ,'class' => 'task-progress-form','id' => 'task-progress-form','data-refresh' => 'load-task-detail']) !!}
				                <div class="text-center">
				                	<input name="progress" class="slider" style="width: 95%;" type="text" data-slider-value="{{$task->progress}}" data-slider-ticks="[0, 25, 50, 75, 100]" data-slider-ticks-snap-bounds="1" data-slider-ticks-labels='["0%", "25%", "50%", "75%", "100%"]'/>
				                </div>
				                {!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right btn-sm','style' => 'margin-top:10px;']) !!}
				                {!! Form::close() !!}
				                <div class="clear"></div>
							</div>
						</div>
						<div class="tab-pane animated fadeInRight" id="sub-task-tab">
							<div class="user-profile-content">
								<div class="row">
									<div class="col-md-12">
										{!! Form::model($task,['method' => 'POST','route' => ['task.add-sub-task',$task->id] ,'class' => 'sub-task-form','id' => 'sub-task-form','data-table-refresh' => 'sub-task-table']) !!}
											<div class="form-group">
												{!! Form::label('title',trans('messages.title'),[])!!}
												{!! Form::input('text','title','',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
											</div>
										  <div class="form-group">
										    {!! Form::textarea('description','',['size' => '30x3', 'class' => 'form-control ', 'placeholder' => trans('messages.description'),'data-autoresize' => 1,"data-show-counter" => 1,"data-limit" => config('config.textarea_limit')])!!}
										    <span class="countdown"></span>
										  </div>
										  <div class="form-group">
										  	{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right btn-sm']) !!}
										  </div>
										{!! Form::close() !!}
									</div>
								</div>
								<div class="row" style="margin-top: 15px;">
									<div class="col-md-12">
										<div class="table-responsive">
											<table data-sortable class="table table-hover table-striped table-bordered ajax-table show-table"  id="sub-task-table" data-source="/sub-task/lists" data-extra="&task_id={{$task->id}}">
												<thead>
													<tr>
														<th>{!! trans('messages.title') !!}</th>
														<th>{!! trans('messages.description') !!}</th>
														<th>{!! trans('messages.user') !!}</th>
														<th>{!! trans('messages.date') !!}</th>
														<th data-sortable="false" >{!! trans('messages.option') !!}</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane animated fadeInRight" id="comment-tab">
							<div class="user-profile-content">
							{!! Form::model($task,['method' => 'POST','route' => ['task-comment.store',$task->id] ,'class' => 'task-comment-form','id' => 'task-comment-form','data-refresh' => 'load-task-comment']) !!}
								  <div class="form-group">
								    {!! Form::textarea('comment','',['size' => '30x1', 'class' => 'form-control ', 'placeholder' => 'Enter Your '.trans('messages.comment'),'data-autoresize' => 1,'style' => 'border:0px;border-bottom:1px solid #cccccc;'])!!}
								    <span class="countdown"></span>
								  </div>
								  {!! Form::submit(trans('messages.post'),['class' => 'btn btn-primary pull-right btn-sm']) !!}
								{!! Form::close() !!}
								<div class="clear"></div>

								<h2><strong>{!! trans('messages.comment') !!}</strong> {!! trans('messages.list') !!}</h2>
								<div id="load-task-comment" data-extra="&id={{$task->id}}" data-source="/task-comment"></div>
							</div>
						</div>
						<div class="tab-pane animated fadeInRight" id="note-tab">
							<div class="user-profile-content">
							{!! Form::model($task,['method' => 'POST','route' => ['task-note.store',$task->id] ,'class' => 'task-note-form','id' => 'task-note-form','data-no-form-clear' => 1]) !!}
							   <div class="form-group">
							    {!! Form::textarea('note',($task->TaskNote->where('user_id',Auth::user()->id)->count()) ? $task->TaskNote->where('user_id',Auth::user()->id)->first()->note : '',['size' => '30x10', 'class' => 'form-control notebook', 'placeholder' => trans('messages.note'),'data-autoresize' => 1])!!}
							    <span class="countdown"></span>
							   </div>
						 	{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
							{!! Form::close() !!}
							<div class="clear"></div>
							</div>
						</div>
						<div class="tab-pane animated fadeInRight" id="attachment-tab">
							<div class="user-profile-content">
							{!! Form::model($task,['files'=>'true','method' => 'POST','route' => ['task-attachment.store',$task->id] ,'class' => 'task-attachment-form','id' => 'task-attachment-form','data-table-refresh' => 'task-attachment-table','data-file-upload' => '.file-uploader']) !!}
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('title',trans('messages.title'),[])!!}
											{!! Form::input('text','title','',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
										</div>
										@include('upload.index',['module' => 'task-attachment','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => ''])
									</div>
									<div class="col-md-6">
										<div class="form-group">
											{!! Form::label('description',trans('messages.description'),[])!!}
											{!! Form::textarea('description','',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
											<span class="countdown"></span>
										</div>
									</div>
								</div>
								{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}	
								{!! Form::close() !!}
								<div class="clear"></div>
								<h2><strong>{!! trans('messages.attachment') !!}</strong> {!! trans('messages.list') !!}</h2>
								<div class="table-responsive">
									<table class="table table-hover table-striped table-bordered ajax-table" id="task-attachment-table" data-source="/task-attachment/lists" data-extra="&task_id={{$task->id}}">
										<thead>
											<tr>
												<th>{!! trans('messages.title') !!}</th>
												<th>{!! trans('messages.description') !!}</th>
												<th>{!! trans('messages.date') !!}</th>
												<th>{!! trans('messages.attachment') !!}</th>
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
				</div>

				<div class="box-info">
					<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.rating') !!}
					</h2>
					@if($task->user_id == Auth::user()->id)
					<div class="additional-btn">
						<a class="additional-icon" id="dropdownMenu4" data-toggle="dropdown">
							<i class="fa fa-cog fa-lg icon"></i>
						</a>
						<ul class="dropdown-menu pull-right animated half flipInX" role="menu" aria-labelledby="dropdownMenu4">
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-ajax="1" data-extra="&task_id={{$task->id}}&sub_task_rating=0" data-source="/task-rating-type" style="color:black;" data-refresh="show-rating-table">{{trans('messages.task').' '.trans('messages.rating')}}</a></li>
							<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-ajax="1" data-extra="&task_id={{$task->id}}&sub_task_rating=1" data-source="/task-rating-type" style="color:black;" data-refresh="show-rating-table">{{trans('messages.sub').' '.trans('messages.task').' '.trans('messages.rating')}}</a></li>
						</ul>
					</div>
					@endif
					@if(!$task->sub_task_rating)
						<div class="table-responsive">
							<table data-sortable class="table table-hover table-striped table-bordered ajax-table show-table" id="task-rating-table" data-source="/task-rating/lists" data-extra="&task_id={{$task->id}}">
								<thead>
									<tr>
										<th>{{ trans('messages.user') }}</th>
										<th>{{ trans('messages.rating') }}</th>
										<th>{{ trans('messages.comment') }}</th>
										<th>{{ trans('messages.date') }}</th>
										@if($task->user_id == Auth::user()->id)
											<th data-sortable="false">{{ trans('messages.option') }}</th>
										@endif
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					@else
						<div class="table-responsive">
							<table data-sortable class="table table-hover table-striped table-bordered ajax-table show-table" id="sub-task-rating-table" data-source="/sub-task-rating/lists" data-extra="&task_id={{$task->id}}">
								<thead>
									<tr>
										<th>{{ trans('messages.user') }}</th>
										<th>{{ trans('messages.rating') }}</th>
										@if($task->user_id == Auth::user()->id)
											<th data-sortable="false">{{ trans('messages.option') }}</th>
										@endif
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					@endif
				</div>
			</div>
			<div class="col-md-4">
                <div class="box-info full">
                   	<h2><strong>{!!trans('messages.task').'</strong> '.trans('messages.detail')!!}
                   		<div class="additional-btn">
							<div class="btn-group">
							  	@if(Entrust::can('edit-task') && $task->user_id == Auth::user()->id)
							  		<a href="#" data-href="/task/{{$task->id}}/edit" class="btn btn-default btn-xs" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
							  	@endif
							  	@if(Entrust::can('delete-task') && $task->user_id == Auth::user()->id)
							  		{!! delete_form(['task.destroy',$task->id],['redirect' => '/task']) !!}
							  	@endif
							</div>
                   		</div>
                   	</h2>
                   		<div id="load-task-detail" data-extra="&id={{$task->id}}" data-source="/task-detail"></div>
                </div>
			</div>
		</div>
	@stop