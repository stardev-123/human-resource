
				@if(isset($att_summary))
				<div class="box-info full">
					<h2><strong>{!!trans('messages.attendance').' </strong>'.trans('messages.summary') !!}</h2>
					<div class="table-responsive">
						<table class="table table-hover table-striped show-table">
							<tbody>
								<tr>
									<th><span class="label label-danger">{!! trans('messages.absent') !!}</span></th>
									<td>{!! $att_summary['A'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-info">{!! trans('messages.holiday') !!}</span></th>
									<td>{!! $att_summary['H'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-warning">{!! trans('messages.half').' '.trans('messages.day') !!}</span></th>
									<td>{!! $att_summary['HD'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-success">{!! trans('messages.present') !!}</span></th>
									<td>{!! $att_summary['P'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-warning">{!! trans('messages.leave') !!}</span></th>
									<td>{!! $att_summary['L'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-primary">{!! trans('messages.late') !!}</span></th>
									<td>{!! $att_summary['Late'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-success">{!! trans('messages.overtime') !!}</span></th>
									<td>{!! $att_summary['Overtime'] !!}</td>
								</tr>
								<tr>
									<th><span class="label label-warning">{!! trans('messages.early_leaving') !!}</span></th>
									<td>{!! $att_summary['Early'] !!}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				@endif
				@if(isset($summary))
				<div class="box-info full">
					<h2><strong>{!! trans('messages.hour').' </strong>'.trans('messages.summary') !!}</h2>
					<div class="table-responsive">
						<table class="table table-hover table-striped show-table">
							<tbody>
								<tr>
									<th><span class="label label-danger">{!! trans('messages.total').' '.trans('messages.late') !!}</span></th>
									<td>{!! array_key_exists('total_late',$summary) ? $summary['total_late'] : '-' !!}</td>
								</tr>
								<tr>
									<th><span class="label label-warning">{!! trans('messages.total').' '.trans('messages.early_leaving') !!}</span></th>
									<td>{!! array_key_exists('total_early_leaving',$summary) ? $summary['total_early_leaving'] : '-' !!}</td>
								</tr>
								<tr>
									<th><span class="label label-info">{!! trans('messages.total').' '.trans('messages.rest') !!}</span></th>
									<td>{!! array_key_exists('total_rest',$summary) ? $summary['total_rest'] : '-' !!}</td>
								</tr>
								<tr>
									<th><span class="label label-success">{!! trans('messages.total').' '.trans('messages.overtime') !!}</span></th>
									<td>{!! array_key_exists('total_overtime',$summary) ? $summary['total_overtime'] : '-' !!}</td>
								</tr>
								<tr>
									<th><span class="label label-primary">{!! trans('messages.total').' '.trans('messages.work') !!}</span></th>
									<td>{!! array_key_exists('total_working',$summary) ? $summary['total_working'] : '-' !!}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				@endif