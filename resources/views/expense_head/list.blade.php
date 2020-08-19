
	@if($expense_heads->count())
		@foreach($expense_heads as $expense_head)
			<tr>
				<td>{{$expense_head->name}}</td>
				<td>{{$expense_head->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/expense-head/{{$expense_head->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['expense-head.destroy',$expense_head->id],['table-refresh' => 'expense-head-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif