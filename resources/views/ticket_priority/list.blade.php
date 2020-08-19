
	@if($ticket_priorities->count())
		@foreach($ticket_priorities as $ticket_priority)
			<tr>
				<td>{{$ticket_priority->name}}</td>
				<td>{{$ticket_priority->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/ticket-priority/{{$ticket_priority->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['ticket-priority.destroy',$ticket_priority->id],['table-refresh' => 'ticket-priority-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif