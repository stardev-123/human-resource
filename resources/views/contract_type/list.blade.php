
	@if($contract_types->count())
		@foreach($contract_types as $contract_type)
			<tr>
				<td>{{$contract_type->name}}</td>
				<td>{{$contract_type->description}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/contract-type/{{$contract_type->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!!delete_form(['contract-type.destroy',$contract_type->id],['table-refresh' => 'contract-type-table'])!!}
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="3">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif