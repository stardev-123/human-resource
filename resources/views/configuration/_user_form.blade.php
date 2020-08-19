
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('designation_level',trans('messages.designation').' '.trans('messages.level'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="designation_level" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.designation_level') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('location_level',trans('messages.location').' '.trans('messages.level'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="location_level" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.location_level') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('user_manage_own_contact',trans('messages.user_manage_own_contact'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="user_manage_own_contact" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.user_manage_own_contact') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('user_manage_own_bank_account',trans('messages.user_manage_own_bank_account'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="user_manage_own_bank_account" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.user_manage_own_bank_account') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('user_manage_own_document',trans('messages.user_manage_own_document'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="user_manage_own_document" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.user_manage_own_document') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('user_manage_own_qualification',trans('messages.user_manage_own_qualification'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="user_manage_own_qualification" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.user_manage_own_qualification') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('user_manage_own_experience',trans('messages.user_manage_own_experience'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                <input name="user_manage_own_experience" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.user_manage_own_experience') == 1) ? 'checked' : '' }} data-off-value="0">
	                </div>
	              </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('list_user_criteria',trans('messages.list_user_criteria'),['class' => 'control-label '])!!}
				    {!! Form::select('list_user_criteria[]',[
				    	'active' => trans('messages.active'),
				    	'inactive' => trans('messages.inactive'),
				    	'pending_approval' => trans('messages.pending').' '.trans('messages.approval'),
				    	'pending_activation' => trans('messages.pending').' '.trans('messages.activation'),
				    	'banned' => trans('messages.banned')
				    ],explode(',',config('config.list_user_criteria')),['class'=>'form-control show-tick','title'=>trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
				  </div>
				</div>
			  </div>
			<input type="hidden" name="config_type" readonly value="user">
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}