	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! config('config.application_name').' '.trans('messages.update') !!}</h4>
	</div>
	<div class="modal-body">
		@if(!count($data))
			@include('global.notification',['type' => 'danger','message' => trans('messages.check_internet_connection')])
		@elseif($data['status'] == 'error')
			@include('global.notification',['type' => 'danger','message' => 'Invalid build. Please contact app author.'])
		@else
		<div class="table-responsive">
			<table class="table table-stripped table-bordered table-hover">
				<thead>
					<tr>
						<th>Version</th>
						<th>Build</th>
						<th>Release Date</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{$data['current_version']}} <span class="label label-info">Current Version</span></td>
						<td>{{env('BUILD')}}</td>
						<td>{{$data['current_date']}}</td>
					</tr>
					@if(array_key_exists('version',$data))
					<tr>
						<td>{{$data['version']}} <span class="label label-success">Update Available</span></td>
						<td>{{$data['build']}}</td>
						<td>{{$data['date']}}</td>
					</tr>
					@else
					<tr>
						<td colspan="3"><span class="label label-danger">No update available.</span></td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		@endif
	</div>