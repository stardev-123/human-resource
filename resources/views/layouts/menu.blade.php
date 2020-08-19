
	<li {!! (in_array('home',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'home') !!}><a href="/home"><i class="fa fa-home icon"></i> {!! trans('messages.home') !!}</a></li>
	@if(Entrust::can('list-user'))
		<li {!! (in_array('user',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'user') !!}><a href="/user"><i class="fa fa-user icon"></i> {!! trans('messages.user') !!}</a></li>
	@endif
	@if(Entrust::can('list-client'))
		<li {!! (in_array('client',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'client') !!}><a href="/client"><i class="fa fa-user icon"></i> {!! trans('messages.client') !!}</a></li>
	@endif
	<li class="list-container {!! (in_array('attendance',$menu)) ? 'active' : '' !!}" {!! menuAttr($menus,'attendance') !!} id="attendance-menu-list"><a href=""><i class="fa fa-book icon"></i><i class="fa fa-angle-double-down i-right"></i> {!! trans('messages.attendance') !!}</a>
		<ul class="list-data {!! (
					in_array('report',$menu) ||
					in_array('shift_report',$menu) ||
					in_array('update_attendance',$menu)
		) ? 'visible' : '' !!}">
			<li class="no-sort {!! (in_array('report',$menu)) ? 'active' : '' !!}"><a href="/daily-attendance"><i class="fa fa-angle-right"></i> {!! trans('messages.attendance').' '.trans('messages.report') !!} </a></li>
			<li class="no-sort {!! (in_array('shift_report',$menu)) ? 'active' : '' !!}"><a href="/daily-shift"><i class="fa fa-angle-right"></i> {!! trans('messages.shift').' '.trans('messages.report') !!} </a></li>
			<li class="no-sort {!! (in_array('update_attendance',$menu)) ? 'active' : '' !!}"><a href="/update-attendance"><i class="fa fa-angle-right"></i> {!! trans('messages.update').' '.trans('messages.attendance') !!} </a></li>
		</ul>
	</li>
	@if(Entrust::can('manage-holiday'))
		<li {!! (in_array('holiday',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'holiday') !!}><a href="/holiday"><i class="fa fa-fighter-jet icon"></i> {!! trans('messages.holiday') !!}</a></li>
	@endif
	<li {!! (in_array('leave',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'leave') !!}><a href="/leave"><i class="fa fa-coffee icon"></i> {!! trans('messages.leave') !!}
		@if($leave_count)
			<span class="badge badge-danger animated double pull-right">{{$leave_count}}</span>
		@endif
	</a></li>
	<li {!! (in_array('payroll',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'payroll') !!}><a href="/payroll"><i class="fa fa-money icon"></i> {!! trans('messages.payroll') !!}</a></li>
	@if(Entrust::can('list-announcement'))
		<li {!! (in_array('announcement',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'announcement') !!}><a href="/announcement"><i class="fa fa-list-alt icon"></i> {!! trans('messages.announcement') !!}</a></li>
	@endif
	@if(Entrust::can('list-library'))
		<li {!! (in_array('library',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'library') !!}><a href="/library"><i class="fa fa-folder icon"></i> {!! trans('messages.library') !!}</a></li>
	@endif
	@if(Entrust::can('list-award'))
		<li {!! (in_array('award',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'award') !!}><a href="/award"><i class="fa fa-trophy icon"></i> {!! trans('messages.award') !!}</a></li>
	@endif
	@if(Entrust::can('list-daily-report'))
		<li {!! (in_array('daily_report',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'daily_report') !!}><a href="/daily-report"><i class="fa fa-bars icon"></i> {!! trans('messages.daily').' '.trans('messages.report') !!}</a></li>
	@endif
	@if(Entrust::can('list-expense'))
		<li {!! (in_array('expense',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'expense') !!}><a href="/expense"><i class="fa fa-credit-card icon"></i> {!! trans('messages.expense') !!}
			@if($expense_count)
				<span class="badge badge-danger animated double pull-right">{{$expense_count}}</span>
			@endif
		</a></li>
	@endif
    @if(Entrust::can('list-task'))
		<li {!! (in_array('task',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'task') !!}><a href="/task"><i class="fa fa-tasks icon"></i> {!! trans('messages.task') !!}
		@if($task_count)
			<span class="badge badge-danger animated double pull-right">{{$task_count}}</span>
		@endif
	</a></li>
	@endif
	@if(Entrust::can('list-ticket'))
		<li {!! (in_array('ticket',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'ticket') !!}><a href="/ticket"><i class="fa fa-ticket icon"></i> {!! trans('messages.ticket') !!}
			@if($ticket_count)
				<span class="badge badge-danger animated double pull-right">{{$ticket_count}}</span>
			@endif
		</a></li>
	@endif
    @if(config('config.enable_message'))
    	<li {!! (in_array('message',$menu)) ? 'class="active"' : '' !!} {!! menuAttr($menus,'message') !!}><a href="/message"><i class="fa fa-envelope icon"></i> {!! trans('messages.message') !!}
		@if($inbox_count)
			<span class="badge badge-danger animated double pull-right">{{$inbox_count}}</span>
		@endif
		</a></li>
    @endif

    @if(Entrust::can('manage-job'))
	<li class="list-container {!! (in_array('job',$menu)) ? 'active' : '' !!}" {!! menuAttr($menus,'job') !!} id="job-menu-list"><a href=""><i class="fa fa-bullhorn icon"></i><i class="fa fa-angle-double-down i-right"></i> {!! trans('messages.job') !!}</a>
		<ul class="list-data {!! (
					in_array('job',$menu) ||
					in_array('job_application',$menu)
		) ? 'visible' : '' !!}" >
			<li class="no-sort {!! (in_array('job',$menu)) ? 'active' : '' !!}"><a href="/job"><i class="fa fa-angle-right"></i> {!! trans('messages.job').' '.trans('messages.post') !!} </a></li>
			<li class="no-sort {!! (in_array('job_application',$menu)) ? 'active' : '' !!}"><a href="/job-application"><i class="fa fa-angle-right"></i> {!! trans('messages.job').' '.trans('messages.application') !!} </a></li>
		</ul>
	</li>
	@endif