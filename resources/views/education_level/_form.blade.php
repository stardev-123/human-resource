
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.level'),[])!!}
				{!! Form::input('text','name',isset($education_level) ? $education_level->name : '',['class'=>'form-control','placeholder'=>trans('messages.level')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($education_level) ? $education_level->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
