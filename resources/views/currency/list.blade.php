	@if(count($currencies))
		@foreach($currencies as $currency)
		<tr>
			<td>{{$currency->name}}</td>
			<td>{{$currency->symbol}}</td>
			<td>{{$currency->position}}</td>
			<td>{!! ($currency->is_default) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' !!}</td>
			<td>
				<div class="btn-group btn-group-xs">
					<a href="#" data-href="/currency/{{$currency->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
					{!! delete_form(['currency.destroy',$currency->id],['table-refresh' => 'currency-table'])!!}
				</div>
			</td>
		</tr>
		@endforeach
	@else
		<tr><td colspan="5">{{trans('messages.no_data_found')}}</td></tr>
	@endif