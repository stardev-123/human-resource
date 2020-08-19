		
		@if($user_leave)
			<div class="help-block" style="padding:0px 10px;">{!! trans('messages.leave').' '.trans('messages.period').' : <strong>'. showDate($user_leave->from_date).' '.trans('messages.to').' '.showDate($user_leave->to_date)!!}</strong> </div>
			<div class="table-responsive">
				<table class="table table-stripped table-hover show-table">
					<tbody>
						<tr>
							<th><i class="fa fa-bell info"></i> {{trans('messages.leave').' '.trans('messages.w_applied')}} </th>
							<td><span class="badge badge-info"> {{$leaves->count()}} </span></td>
						</tr>
						<tr>
							<th><i class="fa fa-thumbs-up success"></i> {{trans('messages.leave').' '.trans('messages.w_approved')}} </th>
							<td><span class="badge badge-success"> {{$leaves->where('status','approved')->count()}} </span></td>
						</tr>
						<tr>
							<th><i class="fa fa-thumbs-down danger"></i> {{trans('messages.leave').' '.trans('messages.w_rejected')}} </th>
							<td><span class="badge badge-danger"> {{$leaves->where('status','rejected')->count()}} </span></td>
						</tr>
						<tr>
							<th><i class="fa fa-hourglass warning"></i> {{trans('messages.leave').' '.trans('messages.pending')}} </th>
							<td><span class="badge badge-warning"> {{$leaves->where('status','pending')->count()}} </span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="margin:10px;">
			@foreach($user_leave_data as $leave_data)
				@if($leave_data['leave_assigned'])
					<p><strong>{{ $leave_data['leave_name'] }} ({{$leave_data['leave_used']}}/{{$leave_data['leave_assigned']}})</strong></p>
					<div class="progress">
						<div class="progress-bar progress-bar-{{$leave_data['leave_used_percentage']}}" role="progressbar" aria-valuenow="{{$leave_data['leave_used_percentage']}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$leave_data['leave_used_percentage']}}%;"></div>
					</div>
				@endif
			@endforeach
			</div>
		@endif