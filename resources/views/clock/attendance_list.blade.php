
	@if($clocks->count())
		@foreach($clocks as $clock)
		<tr>
			<td>{!! showDateTime($clock->clock_in) !!}</td>
			<td>{!! showDateTime($clock->clock_out) !!}</td>
			<td>
				<div class="btn-group btn-group-xs">
			  		<a href="#" data-href="/clock/{{$clock->id}}/edit" class='btn btn-xs btn-default' data-toggle="modal" data-target="#myModal"> <i class='fa fa-edit' data-toggle="tooltip" title="{!! trans('messages.edit') !!}"></i> </a>
			  		{!! delete_form(['clock.destroy',$clock->id],['table-refresh' => 'clock-list-table']) !!}
			  	</div>
			</td>
		</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif