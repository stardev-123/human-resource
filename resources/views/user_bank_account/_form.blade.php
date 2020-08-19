		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('account_name',trans('messages.account').' '.trans('messages.name'))!!}
					{!! Form::input('text','account_name',isset($user_bank_account) ? $user_bank_account->account_name : '',['class'=>'form-control','placeholder'=>trans('messages.account').' '.trans('messages.name')])!!}
				</div>
				<div class="checkbox">
					<label>
					  {!! Form::checkbox('is_primary', 1,(isset($user_bank_account) && $user_bank_account->is_primary) ? 'checked' : '',['class' => 'icheck']) !!} {!! trans('messages.primary').' '.trans('messages.account') !!}
					</label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('account_number',trans('messages.account').' '.trans('messages.number'))!!}
					{!! Form::input('text','account_number',isset($user_bank_account) ? $user_bank_account->account_number : '',['class'=>'form-control','placeholder'=>trans('messages.account').' '.trans('messages.number')])!!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('bank_name',trans('messages.bank').' '.trans('messages.name'))!!}
					{!! Form::input('text','bank_name',isset($user_bank_account) ? $user_bank_account->bank_name : '',['class'=>'form-control','placeholder'=>trans('messages.bank').' '.trans('messages.name')])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('bank_code',trans('messages.bank').' '.trans('messages.code'))!!}
					{!! Form::input('text','bank_code',isset($user_bank_account) ? $user_bank_account->bank_code : '',['class'=>'form-control','placeholder'=>trans('messages.bank').' '.trans('messages.code')])!!}
				</div>
			</div>
		</div>
		<div class="form-group">
		    {!! Form::label('bank_branch',trans('messages.bank').' '.trans('messages.branch'))!!}
			{!! Form::input('text','bank_branch',isset($user_bank_account) ? $user_bank_account->bank_branch : '',['class'=>'form-control','placeholder'=>trans('messages.bank').' '.trans('messages.branch')])!!}
		</div>
		<div class="row">
			<div class="col-md-12">
				{{ getCustomFields('user-bank-account-form',isset($custom_user_bank_account_field_values) ? $custom_user_bank_account_field_values : []) }}
			</div>
		</div>
	    {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		<div class="clear"></div>