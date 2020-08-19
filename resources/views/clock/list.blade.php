
	@if($clocks->count())
		@foreach($clocks as $clock)
			<tr>
				<td>{{showTime($clock->clock_in)}}</td>
				<td>{{showTime($clock->clock_out)}}</td>
			</tr>
		@endforeach
		<tr><th colspan="2" class="text-center">{{trans('messages.summary')}}</th></tr> 

		<tr>
			<th><span class="label label-danger">{!! trans('messages.total').' '.trans('messages.late') !!}</span></th>
			<td>{!! !empty($attendance['summary']['total_late']) ? $attendance['summary']['total_late'] : '00:00' !!}</td>
		</tr>
		<tr>
			<th><span class="label label-info">{!! trans('messages.total').' '.trans('messages.rest') !!}</span></th>
			<td>{!! !empty($attendance['summary']['total_rest']) ? $attendance['summary']['total_rest'] : '00:00' !!}</td>
		</tr>
		<tr>
			<th><span class="label label-warning">{!! trans('messages.total').' '.trans('messages.early_leaving') !!}</span></th>
			<td>{!! !empty($attendance['summary']['total_early_leaving']) ? $attendance['summary']['total_early_leaving'] : '00:00' !!}</td>
		</tr>
		<tr>
			<th><span class="label label-success">{!! trans('messages.total').' '.trans('messages.working') !!}</span></th>
			<td>{!! !empty($attendance['summary']['total_working']) ? $attendance['summary']['total_working'] : '00:00' !!}</td>
		</tr>
		<tr>
			<th><span class="label label-primary">{!! trans('messages.total').' '.trans('messages.overtime') !!}</span></th>
			<td>{!! !empty($attendance['summary']['total_overtime']) ? $attendance['summary']['total_overtime'] : '00:00' !!}</td>
		</tr>
	@else
		<tr>
			<td colspan="2">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif