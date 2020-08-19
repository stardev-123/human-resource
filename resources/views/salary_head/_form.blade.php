
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.head'),[])!!}
				{!! Form::input('text','name',isset($salary_head) ? $salary_head->name : '',['class'=>'form-control','placeholder'=>trans('messages.head')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('type',trans('messages.type'),[])!!}
				{!! Form::select('type', [
							'earning' => trans('messages.earning'),
							'deduction' => trans('messages.deduction'),
				],isset($salary_head) ? $salary_head->type : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('is_fixed',trans('messages.fixed'),['class' => 'control-label '])!!}
                <div class="checkbox">
                <input name="is_fixed" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (isset($salary_head) && $salary_head->is_fixed) ? 'checked' : '' }} data-off-value="0">
                </div>
              </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($salary_head) ? $salary_head->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
