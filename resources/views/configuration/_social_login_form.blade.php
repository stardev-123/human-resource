	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				{!! Form::label('enable_social_login',trans('messages.enable').' Social Login',['class' => 'control-label '])!!}
				<div class="checkbox">
					<input name="enable_social_login" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_social_login') == 1) ? 'checked' : '' }} data-off-value="0">
				</div>
			</div>
		</div>
		<div id="enable_social_login_field">
			@foreach(config('constant.social_login_provider') as $provider)
				<div class="col-md-4">
					<div class="form-group">
						{!! Form::label('enable_'.$provider.'_login',trans('messages.enable').' '.toWord($provider).' Login',['class' => 'control-label '])!!}
						<div class="checkbox">
							<input name="enable_{{$provider}}_login" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_'.$provider.'_login') == 1) ? 'checked' : '' }} data-off-value="0">
						</div>
					</div>
					<div id="enable_{{$provider}}_login_field">
						<div class="form-group">
							{!! Form::label($provider.'_client_id',toWord($provider).' Client Id',[])!!}
							{!! Form::input('text',$provider.'_client_id',(config('config.'.$provider.'_client_id')) ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>toWord($provider).' Client Id'])!!}
						</div>
						<div class="form-group">
							{!! Form::label($provider.'_client_secret',toWord($provider).' Client Secret',[])!!}
							{!! Form::input('text',$provider.'_client_secret',(config('config.'.$provider.'_client_secret')) ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>toWord($provider).' Client Secret'])!!}
						</div>
						<div class="form-group">
							{!! Form::label($provider.'_redirect',toWord($provider).' App Redirect URL',[])!!}
							{!! Form::input('text',$provider.'_redirect',(config('config.'.$provider.'_redirect')) ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>toWord($provider).' App Redirect URL'])!!}
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="form-group">
		<input type="hidden" name="config_type" class="hidden_fields" value="social_login">
		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
	</div>