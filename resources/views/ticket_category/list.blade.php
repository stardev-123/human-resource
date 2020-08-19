
	@if($ticket_categories->count())
		@foreach($ticket_categories as $ticket_category)
			<tr>
				<td>{{$ticket_category->name}}</td>
				<td>{{$ticket_category->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/ticket-category/{{$ticket_category->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['ticket-category.destroy',$ticket_category->id],['table-refresh' => 'ticket-category-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif