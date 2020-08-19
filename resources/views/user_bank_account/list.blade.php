
	@if($user->UserBankAccount->count())
		@foreach($user->UserBankAccount as $user_bank_account)
			<tr>
				<td>{!!$user_bank_account->account_name.' '.(($user_bank_account->is_primary) ? '<span class="label label-success">'.trans('messages.primary').'</span>' : '')!!}</td>
				<td>{{$user_bank_account->account_number}}</td>
				<td>{{$user_bank_account->bank_name}}</td>
				<td>{{$user_bank_account->bank_branch}}</td>

				<td><div class="btn-group btn-group-xs">
					<a href="#" data-href="/user-bank-account/{{$user_bank_account->id}}" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="{{trans('messages.view')}}"></i></a>

					@if((Entrust::can('edit-user') && $user_bank_account->user_id != \Auth::user()->id) || !count(getParent()))
						@if($user_bank_account->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_bank_account->id}}" data-source="/user-bank-account/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-bank-account-table"><i class="fa fa-unlock" data-toggle="tooltip" title="{{trans('messages.unlock')}}"></i></a>
						@else(!$user_bank_account->is_locked)
							<a href="#" data-ajax="1" data-extra="&id={{$user_bank_account->id}}" data-source="/user-bank-account/toggle-lock" class="click-alert-message btn btn-sm btn-default" data-table-refresh="user-bank-account-table"><i class="fa fa-lock" data-toggle="tooltip" title="{{trans('messages.lock')}}"></i></a>
						@endif
					@endif

					@if(!$user_bank_account->is_locked && ($user_bank_account->user_id == \Auth::user()->id || ($user_bank_account->user_id != \Auth::user()->id && Entrust::can('edit-user'))))
						<a href="#" data-href="/user-bank-account/{{$user_bank_account->id}}/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal" ><i class="fa fa-edit" data-toggle="tooltip" title="{{trans('messages.edit')}}"></i></a>
						{!!delete_form(['user-bank-account.destroy',$user_bank_account->id],['table-refresh' => 'user-bank-account-table','refresh-content' => 'load-user-detail'])!!}
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