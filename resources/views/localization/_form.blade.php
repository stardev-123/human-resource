
			  <div class="form-group">
			    {!! Form::label('locale',trans('messages.locale'),[])!!}
			  	@if(!isset($locale))
					{!! Form::input('text','locale',isset($locale) ? $locale : '',['class'=>'form-control','placeholder'=>trans('messages.locale')])!!}
				@else
					{!! Form::input('text','locale',isset($locale) ? $locale : '',['class'=>'form-control','placeholder'=>trans('messages.locale'),'readonly' => 'true'])!!}
				@endif	
			  </div>
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.localization').' '.trans('messages.name'),[])!!}
				{!! Form::input('text','name',isset($locale) ? config('localization.'.$locale.'.localization') : '',['class'=>'form-control','placeholder'=>trans('messages.localization').' '.trans('messages.name')])!!}
			  </div>
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
