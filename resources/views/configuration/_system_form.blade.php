			<div class="col-sm-6">
			  <div class="form-group">
			    {!! Form::label('application_name',trans('messages.application').' '.trans('messages.name'),[])!!}
				{!! Form::input('text','application_name',(config('config.application_name')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.application').' '.trans('messages.name')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('timezone_id',trans('messages.timezone'),[])!!}
				{!! Form::select('timezone_id', config('timezone'),(config('config.timezone_id')) ? : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('default_localization',trans('messages.default').' '.trans('messages.localization'),[])!!}
				{!! Form::select('default_localization', $localizations,(config('config.default_localization')) ? : 'en',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('date_format','Date Format',[])!!}
				{!! Form::select('date_format', [
							'Y-m-d' => date('Y-m-d'),
							'm-d-Y' => date('m-d-Y'),
							'M-d-Y' => date('M-d-Y'),
							'd-m-Y' => date('d-m-Y')
				],(config('config.date_format')) ? : 'd-m-Y',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('time_format','Time Format',['class' => 'control-label '])!!}
                <div class="checkbox">
                	<input name="time_format" type="checkbox" class="switch-input" data-size="mini" data-on-text="12 Hours" data-off-text="24 Hours" value="1" {{ (config('config.time_format') == 1) ? 'checked' : '' }} data-off-value="0">
                </div>
              </div>
			  <div class="form-group">
			    {!! Form::label('credit',trans('messages.credit'),[])!!}
				{!! Form::input('text','credit',(config('config.credit')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.credit')])!!}
			  </div>
			</div>
			<div class="col-sm-6">
			  <div class="form-group">
			    {!! Form::label('notification_position',trans('messages.notification_position'),[])!!}
				{!! Form::select('notification_position', [
					'toast-top-right'=>trans('messages.top_right'),
					'toast-top-left' => trans('messages.top_left'),
					'toast-bottom-right' => trans('messages.bottom_right'),
					'toast-bottom-left' => trans('messages.bottom_left')
					],(config('config.notification_position')) ? : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('setup_guide',trans('messages.setup_guide'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="setup_guide" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.setup_guide') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('error_display',trans('messages.error').' '.trans('messages.display'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="error_display" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.error_display') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('multilingual',trans('messages.multilingual'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="multilingual" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.multilingual') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_ip_filter',trans('messages.enable').' Ip '.trans('messages.filter'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_ip_filter" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_ip_filter') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_activity_log',trans('messages.enable').' '.trans('messages.activity').' ' .trans('messages.log'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_activity_log" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_activity_log') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
			  	  <div class="form-group">
				    {!! Form::label('enable_email_log',trans('messages.enable').' '.trans('messages.email').' ' .trans('messages.log'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_email_log" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_email_log') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_email_template',trans('messages.enable').' '.trans('messages.email').' ' .trans('messages.template'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_email_template" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_email_template') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_to_do',trans('messages.enable').' '.trans('messages.to_do'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_to_do" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_to_do') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_message',trans('messages.enable').' '.trans('messages.message'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_message" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_message') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_backup',trans('messages.enable').' '.trans('messages.backup'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_backup" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_backup') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_custom_field',trans('messages.enable').' '.trans('messages.custom').' '.trans('messages.field'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_custom_field" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_custom_field') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('enable_group_chat',trans('messages.enable').' '.trans('messages.group').' '.trans('messages.chat'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="enable_group_chat" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_group_chat') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
				  <div id="enable_group_chat_field">
					  <div class="form-group">
					    {!! Form::label('enable_chat_refresh',trans('messages.enable').' '.trans('messages.chat').' Refresh',['class' => 'control-label '])!!}
		                <div class="checkbox">
		                <input name="enable_chat_refresh" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_chat_refresh') == 1) ? 'checked' : '' }} data-off-value="0">
		                </div>
		              </div>
				  </div>
				  <div id="enable_chat_refresh_field">
					<div class="form-group">
						{!! Form::label('chat_refresh_duration',trans('messages.chat').' Refresh Duration (In Second)',[])!!}
						{!! Form::input('text','chat_refresh_duration',(config('config.chat_refresh_duration')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.chat').' Refresh Duration (In Second)'])!!}
					</div>
				  </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('maintenance_mode',trans('messages.maintenance').' '.trans('messages.mode'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="maintenance_mode" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.maintenance_mode') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
				  <div id="maintenance_mode_field">
					<div class="form-group">
					    {!! Form::label('under_maintenance_message',trans('messages.under_maintenance_message'),[])!!}
					    {!! Form::textarea('under_maintenance_message',config('config.under_maintenance_message'),['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.under_maintenance_message'),"data-show-counter" => 1,'data-autoresize' => 1])!!}
					    <span class="countdown"></span>
					</div>
				  </div>
			  	</div>
			  </div>
			</div>
		  	<input type="hidden" name="config_type" class="hidden_fields" value="system">
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
			<div class="clear"></div>
