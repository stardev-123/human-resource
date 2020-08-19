	<div class="btn-group">
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{toWordTranslate($current_report)}} <span class="caret"></span>
		</button>
		<ul class="dropdown-menu dropdown-menu-right">
			@foreach(attendanceReport() as $attendance_report_menu)
				@if($attendance_report_menu != $current_report)
				<li><a href="/{{$attendance_report_menu}}">{{toWordTranslate($attendance_report_menu)}}</a></li>
				@endif
			@endforeach
		</ul>
	</div>