
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.name'),[])!!}
				{!! Form::input('text','name',isset($qualification_language) ? $qualification_language->name : '',['class'=>'form-control','placeholder'=>trans('messages.name')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($qualification_language) ? $qualification_language->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
