
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.department').' '.trans('messages.name'),[])!!}
				{!! Form::input('text','name',isset($department) ? $department->name : '',['class'=>'form-control','placeholder'=>trans('messages.department').' '.trans('messages.name')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($department) ? $department->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			  </div>
				{{ getCustomFields('department-form',$custom_field_values) }}
				{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
