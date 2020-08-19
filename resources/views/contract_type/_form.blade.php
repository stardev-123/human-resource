
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.type'),[])!!}
				{!! Form::input('text','name',isset($contract_type) ? $contract_type->name : '',['class'=>'form-control','placeholder'=>trans('messages.type')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($contract_type) ? $contract_type->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
