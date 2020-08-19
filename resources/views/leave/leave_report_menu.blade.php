	<div class="btn-group">
		<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{(isset($current_report)) ? toWordTranslate($current_report) : trans('messages.report')}} <span class="caret"></span>
		</button>
		<ul class="dropdown-menu dropdown-menu-right">
			@foreach(leaveReport() as $leave_report_menu)
				@if(!isset($current_report) || ( isset($current_report) && $leave_report_menu != $current_report))
				<li><a href="/{{$leave_report_menu}}">{{toWordTranslate($leave_report_menu)}}</a></li>
				@endif
			@endforeach
		</ul>
	</div>