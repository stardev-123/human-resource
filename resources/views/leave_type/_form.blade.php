
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.type'),[])!!}
				{!! Form::input('text','name',isset($leave_type) ? $leave_type->name : '',['class'=>'form-control','placeholder'=>trans('messages.type')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($leave_type) ? $leave_type->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
			  <div class="form-group">
                <input name="is_half_day" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (isset($leave_type) && $leave_type->is_half_day) ? 'checked' : '' }}> {{trans('messages.half').' '.trans('messages.day')}}
              </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
