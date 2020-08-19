	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label('session_lifetime',trans('messages.session').' '.trans('messages.lifetime').' (In Min)',[])!!}
				{!! Form::input('text','session_lifetime',(config('config.session_lifetime')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.session').' '.trans('messages.lifetime')])!!}
			</div>
			<div class="form-group">
				{!! Form::label('reset_token_lifetime',trans('messages.reset').' '.trans('messages.token').' '.trans('messages.lifetime').' (In Min)',[])!!}
				{!! Form::input('text','reset_token_lifetime',(config('config.reset_token_lifetime')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.reset').' '.trans('messages.token').' '.trans('messages.lifetime')])!!}
			</div>
			<div class="form-group">
				{!! Form::label('enable_two_factor_auth',trans('messages.enable').' Two factor Auth',['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_two_factor_auth" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_two_factor_auth') == '1') ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div id="enable_two_factor_auth_field">
				<div class="form-group">
					{!! Form::label('two_factor_auth_type','Two factor Auth Type',['class' => 'control-label '])!!}
					<div class="checkbox">
						<input name="two_factor_auth_type" type="checkbox" class="switch-input" data-size="mini" data-on-text="Email" data-off-text="SMS" value="1" {{ (config('config.two_factor_auth_type') == '1') ? 'checked' : '' }} data-off-value="0">
					</div>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_lock_screen',trans('messages.enable').' '.trans('messages.lock_screen'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_lock_screen" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_lock_screen') == '1') ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div id="enable_lock_screen_field">
				<div class="form-group">
					{!! Form::label('lock_screen_timeout',trans('messages.lock_screen').' '.trans('messages.timeout').' (In Min)',[])!!}
					{!! Form::input('text','lock_screen_timeout',(config('config.lock_screen_timeout')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.lock_screen').' '.trans('messages.timeout')])!!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_throttle',trans('messages.enable').' Throttle',['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_throttle" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_throttle') == '1') ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div id="enable_throttle_field">
				<div class="form-group">
					{!! Form::label('throttle_attempt','Throttle Attempt',[])!!}
					{!! Form::input('text','throttle_attempt',(config('config.throttle_attempt')) ? : '',['class'=>'form-control','placeholder'=>'Throttle Attempt'])!!}
				</div>
				<div class="form-group">
					{!! Form::label('throttle_lockout_period','Throttle Lockout Period (In Min)',[])!!}
					{!! Form::input('text','throttle_lockout_period',(config('config.throttle_lockout_period')) ? : '',['class'=>'form-control','placeholder'=>'Throttle Lockout Period'])!!}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label('enable_login_as_user',trans('messages.enable').' '.trans('messages.login').' '.trans('messages.as').' '.trans('messages.user'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_login_as_user" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_login_as_user') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('login_type',trans('messages.login').' '.trans('messages.type'),['class' => 'control-label '])!!}
				{!! Form::select('login_type', [
							'username' => trans('messages.username'),
							'email' => trans('messages.email'),
							'username_or_email' => trans('messages.username').' '.trans('messages.or').' '.trans('messages.email'),
				],(config('config.login_type')) ? : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			</div>
			<div class="form-group">
				{!! Form::label('enable_user_registration',trans('messages.enable').' '.trans('messages.user').' '.trans('messages.registration'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_user_registration" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_user_registration') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_password_strength_meter',trans('messages.enable').' '.trans('messages.password_strength_meter'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_password_strength_meter" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_password_strength_meter') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_email_verification',trans('messages.enable').' '.trans('messages.email').' '.trans('messages.verification'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_email_verification" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_email_verification') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_account_approval',trans('messages.enable').' '.trans('messages.account').' '.trans('messages.approval'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_account_approval" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_account_approval') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label('enable_tnc',trans('messages.enable').' '.trans('messages.tnc'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_tnc" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_tnc') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_remember_me',trans('messages.enable').' '.trans('messages.remember').' '.trans('messages.me'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_remember_me" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_remember_me') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_reset_password',trans('messages.enable').' '.trans('messages.forgot').' '.trans('messages.password'),['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_reset_password" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_reset_password') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('session_expire_browser_close','Session Expire on Browser Close',['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="session_expire_browser_close" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.session_expire_browser_close') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enable_recaptcha',trans('messages.enable').' Recaptcha',['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_recaptcha" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_recaptcha') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
			<div id="enable_recaptcha_field">
				<div class="form-group">
					{!! Form::label('enable_recaptcha_login',trans('messages.enable').' '.trans('messages.login').' Recaptcha',['class' => 'control-label '])!!}
					<div class="checkbox">
						<input name="enable_recaptcha_login" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_recaptcha_login') == 1) ? 'checked' : '' }} data-off-value="0">
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('enable_recaptcha_registration',trans('messages.enable').' '.trans('messages.registration').' Recaptcha',['class' => 'control-label '])!!}
					<div class="checkbox">
						<input name="enable_recaptcha_registration" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_recaptcha_registration') == 1) ? 'checked' : '' }} data-off-value="0">
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('enable_recaptcha_reset_password',trans('messages.enable').' '.trans('messages.reset').' '.trans('messages.password').' Recaptcha',['class' => 'control-label '])!!}
					<div class="checkbox">
						<input name="enable_recaptcha_reset_password" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_recaptcha_reset_password') == 1) ? 'checked' : '' }} data-off-value="0">
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('recaptcha_key','Recaptcha Key',[])!!}
					{!! Form::input('text','recaptcha_key',(config('config.recaptcha_key')) ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'Recaptcha Key'])!!}
				</div>
				<div class="form-group">
					{!! Form::label('recaptcha_secret','Recaptcha Secret',[])!!}
					{!! Form::input('text','recaptcha_secret',(config('config.recaptcha_secret')) ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'Recaptcha Secret'])!!}
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<input type="hidden" name="config_type" class="hidden_fields" value="authentication">
		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
	</div>