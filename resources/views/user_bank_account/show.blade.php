
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user_bank_account->User->full_name.' '.trans('messages.bank').' '.trans('messages.account') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="table-responsive">
            <table data-sortable class="table table-hover table-striped table-bordered">
                <tbody>
                	<tr>
                		<th>{{trans('messages.account').' '.trans('messages.name')}}</th>
                		<td>{{$user_bank_account->account_name}}
                	</tr>
                	<tr>
                		<th>{{trans('messages.account').' '.trans('messages.number')}}</th>
                		<td>{{$user_bank_account->account_number}}
                	</tr>
                	<tr>
                		<th>{{trans('messages.bank').' '.trans('messages.name')}}</th>
                		<td>{{$user_bank_account->bank_name}}
                	</tr>
                	<tr>
                		<th>{{trans('messages.bank').' '.trans('messages.code')}}</th>
                		<td>{{$user_bank_account->bank_code}}
                	</tr>
                	<tr>
                		<th>{{trans('messages.bank').' '.trans('messages.branch')}}</th>
                		<td>{{$user_bank_account->bank_branch}}
                	</tr>
                    <tr>
                        <th>{{trans('messages.created_at')}}</th>
                        <td>{{showDateTime($user_bank_account->created_at)}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('messages.updated_at')}}</th>
                        <td>{{showDateTime($user_bank_account->updated_at)}}</td>
                    </tr>
                    @if(config('config.enable_custom_field'))
                    	@foreach($custom_fields as $custom_field)
                            <tr>
                                <th>{{$custom_field->title}}</th>
                                <td>{!!isset($values[$user_bank_account->id][$custom_field->id]) ? $values[$user_bank_account->id][$custom_field->id] : ''!!}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
	</div>