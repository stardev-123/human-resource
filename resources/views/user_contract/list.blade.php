
	@if($user->UserContract->count())
		@foreach($user->UserContract as $user_contract)
			<tr>
				<td>{!!$user_contract->ContractType->name !!}</td>
				<td>{!!$user_contract->title!!}</td>
				<td>{{showDate($user_contract->from_date)}}</td>
				<td>{{showDate($user_contract->to_date)}}</td>
				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-contract/{{$user_contract->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>
					@if(Entrust::can('edit-user'))
						<a href="#" data-href="/user-contract/{{$user_contract->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-contract.destroy',$user_contract->id],['table-refresh' => 'user-contract-table','refresh-content' => 'load-user-detail'])!!}
					@endif
					</div>
				</td>
			</tr>
		@endforeach
	@else
		<tr>
			<td colspan="5">{{trans('messages.no_data_found')}}</td>
		</tr>
	@endif