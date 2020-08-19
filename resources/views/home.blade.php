@extends('layouts.app')

@section('content')

    @if(defaultRole())
    
    <div class="row">
        <div class="col-sm-3 col-xs-6">
            <div class="box-info">
                <div class="icon-box">
                    <span class="fa-stack">
                      <i class="fa fa-circle fa-stack-2x danger"></i>
                      <i class="fa fa-sitemap fa-stack-1x fa-inverse"></i>
                      <!-- <strong class="fa-stack-1x icon-stack">R</strong> -->
                    </span>
                </div>
                <div class="text-box">
                    <h3>{!! \App\Location::count() !!}</h3>
                    <p>{!! trans('messages.location') !!}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="box-info">
                <div class="icon-box">
                    <span class="fa-stack">
                      <i class="fa fa-circle fa-stack-2x info"></i>
                      <i class="fa fa-bank fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="text-box">
                    <h3>{!! \App\Department::count() !!}</h3>
                    <p>{!! trans('messages.department') !!}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="box-info">
                <div class="icon-box">
                    <span class="fa-stack">
                      <i class="fa fa-circle fa-stack-2x success"></i>
                      <i class="fa fa-user fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="text-box">
                    <h3>{!! \App\Designation::count() !!}</h3>
                    <p>{!! trans('messages.designation') !!}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="col-sm-3 col-xs-6">
            <div class="box-info">
                <div class="icon-box">
                    <span class="fa-stack">
                      <i class="fa fa-circle fa-stack-2x warning"></i>
                      <i class="fa fa-users fa-stack-1x fa-inverse"></i>
                    </span>
                </div>
                <div class="text-box">
                    <h3>{!! getAccessibleUser()->count() !!}</h3>
                    <p>{!! trans('messages.total').' '.trans('messages.user') !!}</p>
                </div>
                <div class="clear"></div>
            </div>
        </div></a>
    </div>
    @endif
<div class="row">
        <div class="col-md-12">
            <div class="box-info">
                <div id="weekly-attendance-statistics-graph"></div>
            </div>
        </div>
    </div>
     <div class="row">
        <div class="col-md-8">
            <div class="box-info">
                <h2>
                    {{trans('messages.calendar')}}
                </h2>
                <div id="render_calendar">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @if(config('config.enable_group_chat'))
            <div class="box-info">
                <h2>
                    <strong>{{trans('messages.group')}}</strong> {{trans('messages.chat')}}
                </h2>
                <div id="chat-box" class="chat-widget custom-scrollbar">
                    <div id="chat-messages" data-chat-refresh="{{config('config.enable_chat_refresh')}}" data-chat-refresh-duration="{{ config('config.chat_refresh_duration') }}"></div>
                </div>
                {!! Form::open(['route' => 'chat.store','role' => 'form', 'class'=>'chat-form input-chat','id' => 'chat-form','data-refresh' => 'chat-messages']) !!}
                {!! Form::input('text','message','',['class'=>'form-control','data-autoresize' => 1,'placeholder' => 'Type your message here..'])!!}
                {!! Form::close() !!}
            </div>
            @endif
            <div class="box-info">
                <h2>
                    {{trans('messages.celebration')}}
                </h2>
                <div id="chat-box" class="chat-widget custom-scrollbar">
                    <ul class="media-list">
                    @foreach($celebrations as $celebration)
                      <li class="media">
                        <a class="pull-left" href="#">
                          {!! getAvatar($celebration['id'],55) !!}
                        </a>
                        <div class="media-body success">
                          <p class="media-heading"><i class="fa fa-{{ $celebration['icon'] }} icon" style="margin-right:10px;"></i> {{ $celebration['title'] }} ({!! $celebration['number'] !!})</p>
                          <p style="margin-bottom:5px;"><strong>{!! $celebration['name'] !!}</strong></p>
                        </div>
                      </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            
             <div class="box-info">
                <h2><strong>{!! trans('messages.announcement') !!}</strong> </h2>
                <div class="custom-scrollbar">
                @if(count($announcements))
                    @foreach($announcements as $announcement)
                        <div class="the-notes info">
                            <h4><a href="#" data-href="/announcement/{{$announcement->id}}" data-toggle="modal" data-target="#myModal">{!! $announcement->title !!}</a></h4>
                            <span style="color:green;"><i class="fa fa-clock-o"></i> {!! showDateTime($announcement->created_at) !!}</span>
                            <p class="time pull-right" style="text-align:right;">{!! trans('messages.by').' '.$announcement->UserAdded->full_name.'<br />'.$announcement->UserAdded->designation_with_department !!}</p>
                        </div>
                    @endforeach
                @else
                    @include('global.notification',['type' => 'danger','message' => trans('messages.no_data_found')])
                @endif
                </div>
            </div>
        </div>
    </div>
   <!-- 
 <div class="row">
        <div class="col-sm-6">
            <div class="box-info">
                <h2><strong>{!! trans('messages.announcement') !!}</strong> </h2>
                <div class="custom-scrollbar">
                @if(count($announcements))
                    @foreach($announcements as $announcement)
                        <div class="the-notes info">
                            <h4><a href="#" data-href="/announcement/{{$announcement->id}}" data-toggle="modal" data-target="#myModal">{!! $announcement->title !!}</a></h4>
                            <span style="color:green;"><i class="fa fa-clock-o"></i> {!! showDateTime($announcement->created_at) !!}</span>
                            <p class="time pull-right" style="text-align:right;">{!! trans('messages.by').' '.$announcement->UserAdded->full_name.'<br />'.$announcement->UserAdded->designation_with_department !!}</p>
                        </div>
                    @endforeach
                @else
                    @include('global.notification',['type' => 'danger','message' => trans('messages.no_data_found')])
                @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box-info">
                <h2><strong>{!! trans('messages.company').' '.trans('messages.hierarchy') !!}</strong></h2>
                <div class="custom-scrollbar" >
                    <h4><strong>{!! trans('messages.you').' : '.Auth::user()->designation_with_department !!}
                    </strong></h4>
                    {!! createLineTreeView($tree,Auth::user()->Profile->designation_id) !!}
                </div>
            </div>
        </div>
    </div>
 -->

    @if($my_shift)
    <div class="row">
        <div class="col-md-4">
            <div class="box-info full">
                <h2><strong>{{ trans('messages.attendance') }}</strong> </h2>
                <div class="additional-btn">
                    @if(Entrust::can('upload-attendance'))
                        {!! Form::model('attendance',['files' => 'true','method' => 'POST','route' => ['upload-column','attendance'] ,'class' => 'form-inline upload-attendance-form','id' => 'upload-attendance-form', 'data-submit' => 'noAjax']) !!}
                          <div class="form-group">
                            <label class="sr-only" for="file">{!! trans('messages.upload').' '.trans('messages.file') !!}</label>
                            <input type="file" name="file" id="file" class="btn btn-info btn-sm file-input" title="{!! trans('messages.select').' '.trans('messages.file') !!}">
                          </div>
                          {!! Form::submit(trans('messages.upload'),['class' => 'btn btn-primary btn-sm']) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
                <div class="custom-scrollbar">
                    <div class="help-block" style="padding:0px 10px;">
                    {!! trans('messages.date').' : <strong>'. showDate(date('Y-m-d'))!!}</strong> <br />
                    {!! trans('messages.my').' '.trans('messages.shift') !!} : <strong>{!! showTime($my_shift->in_time).' to '.showTime($my_shift->out_time) !!}</strong></div>
                    <div style="padding:10px;" id="load-clock-button" data-source="/clock/button"></div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped ajax-table"  id="clock-list-table" data-source="/clock/lists">
                            <thead>
                                <tr>
                                    <th>{!! trans('messages.clock_in') !!}</th>
                                    <th>{!! trans('messages.clock_out') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box-info full">
                <h2><strong>{{ trans('messages.attendance').' '.trans('messages.statistics') }}</strong></h2>
                <div class="custom-scrollbar">
                    <div class="help-block" style="padding:0px 10px;">{!! trans('messages.date').' : <strong>'. showDate(date('Y-m-d'))!!}</strong> </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped show-table" id="attendance-statistics-table">
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box-info full">
                <h2><strong>{{ trans('messages.leave').' '.trans('messages.status') }}</strong> </h2>
                <div class="custom-scrollbar">
                    <div id="load-leave-current-status" data-source="/leave/current-status"></div>
                </div>
            </div>
        </div>
    </div>
    @endif

    
   
    
    <div class="row">
        <div class="col-md-12">
            <div class="box-info full">
                <ul class="nav nav-tabs nav-justified tab-list">
                  <li><a href="#starred-task-tab" data-toggle="tab"><i class="fa fa-star"></i> {!! trans('messages.starred').' '.trans('messages.task') !!}</a></li>
                  <li><a href="#pending-task-tab" data-toggle="tab"><i class="fa fa-battery-half"></i> {!! trans('messages.pending').' '.trans('messages.task') !!}</a></li>
                  <li><a href="#overdue-task-tab" data-toggle="tab"><i class="fa fa-fire"></i> {!! trans('messages.overdue').' '.trans('messages.task') !!}</a></li>
                  <li><a href="#owned-task-tab" data-toggle="tab"><i class="fa fa-user"></i> {!! trans('messages.owned').' '.trans('messages.task') !!}</a></li>
                  <li><a href="#unassigned-task-tab" data-toggle="tab"><i class="fa fa-user-plus"></i> {!! trans('messages.unassigned').' '.trans('messages.task') !!}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane animated fadeInRight" id="starred-task-tab">
                        <div class="user-profile-content custom-scrollbar">
                            <div class="table-responsive">
                                <table data-sortable class="table table-bordered table-hover table-striped ajax-table show-table" id="task-starred-table" data-source="/task/fetch" data-extra="&type=starred">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('messages.title') !!}</th>
                                            <th>{!! trans('messages.status') !!}</th>
                                            <th>{!! trans('messages.category') !!}</th>
                                            <th>{!! trans('messages.priority') !!}</th>
                                            <th>{!! trans('messages.progress') !!}</th>
                                            <th>{!! trans('messages.start').' '.trans('messages.date') !!}</th>
                                            <th>{!! trans('messages.due').' '.trans('messages.date') !!}</th>
                                            <th data-sortable="false">{!! trans('messages.option') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane animated fadeInRight" id="pending-task-tab">
                        <div class="user-profile-content custom-scrollbar">
                            <div class="table-responsive">
                                <table data-sortable class="table table-bordered table-hover table-striped ajax-table show-table" id="task-pending-table" data-source="/task/fetch" data-extra="&type=pending">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('messages.title') !!}</th>
                                            <th>{!! trans('messages.status') !!}</th>
                                            <th>{!! trans('messages.category') !!}</th>
                                            <th>{!! trans('messages.priority') !!}</th>
                                            <th>{!! trans('messages.progress') !!}</th>
                                            <th>{!! trans('messages.start').' '.trans('messages.date') !!}</th>
                                            <th>{!! trans('messages.due').' '.trans('messages.date') !!}</th>
                                            <th data-sortable="false">{!! trans('messages.option') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane animated fadeInRight" id="overdue-task-tab">
                        <div class="user-profile-content custom-scrollbar">
                            <div class="table-responsive">
                                <table data-sortable class="table table-bordered table-hover table-striped ajax-table show-table" id="task-overdue-table" data-source="/task/fetch" data-extra="&type=overdue">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('messages.title') !!}</th>
                                            <th>{!! trans('messages.status') !!}</th>
                                            <th>{!! trans('messages.category') !!}</th>
                                            <th>{!! trans('messages.priority') !!}</th>
                                            <th>{!! trans('messages.progress') !!}</th>
                                            <th>{!! trans('messages.start').' '.trans('messages.date') !!}</th>
                                            <th>{!! trans('messages.due').' '.trans('messages.date') !!}</th>
                                            <th data-sortable="false">{!! trans('messages.option') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane animated fadeInRight" id="owned-task-tab">
                        <div class="user-profile-content custom-scrollbar">
                            <div class="table-responsive">
                                <table data-sortable class="table table-bordered table-hover table-striped ajax-table show-table" id="task-owned-table" data-source="/task/fetch" data-extra="&type=owned">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('messages.title') !!}</th>
                                            <th>{!! trans('messages.status') !!}</th>
                                            <th>{!! trans('messages.category') !!}</th>
                                            <th>{!! trans('messages.priority') !!}</th>
                                            <th>{!! trans('messages.progress') !!}</th>
                                            <th>{!! trans('messages.start').' '.trans('messages.date') !!}</th>
                                            <th>{!! trans('messages.due').' '.trans('messages.date') !!}</th>
                                            <th data-sortable="false">{!! trans('messages.option') !!}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane animated fadeInRight" id="unassigned-task-tab">
                        <div class="user-profile-content custom-scrollbar">
                            <div class="table-responsive">
                                <table data-sortable class="table table-bordered table-hover table-striped ajax-table show-table" id="task-unassigned-table" data-source="/task/fetch" data-extra="&type=unassigned">
                                    <thead>
                                        <tr>
                                            <th>{!! trans('messages.title') !!}</th>
                                            <th>{!! trans('messages.status') !!}</th>
                                            <th>{!! trans('messages.category') !!}</th>
                                            <th>{!! trans('messages.priority') !!}</th>
                                            <th>{!! trans('messages.progress') !!}</th>
                                            <th>{!! trans('messages.start').' '.trans('messages.date') !!}</th>
                                            <th>{!! trans('messages.due').' '.trans('messages.date') !!}</th>
                                            <th data-sortable="false">{!! trans('messages.option') !!}</th>
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
        </div>
    </div>
@endsection
