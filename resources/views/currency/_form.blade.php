
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.currency').' '.trans('messages.name'),[])!!}
				{!! Form::input('text','name',isset($currency) ? $currency->name : '',['class'=>'form-control','placeholder'=>trans('messages.currency').' '.trans('messages.name')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.currency').' '.trans('messages.symbol'),[])!!}
				{!! Form::input('text','symbol',isset($currency) ? $currency->symbol : '',['class'=>'form-control','placeholder'=>trans('messages.currency').' '.trans('messages.symbol')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('position',trans('messages.currency').' '.trans('messages.position'),[])!!}
				{!! Form::select('position', [
					'prefix'=>trans('messages.prefix'),
					'suffix' => trans('messages.suffix')
					],isset($currency) ? $currency->position : '',['class'=>'form-control show-tick','title'=>trans('messages.position')])!!}
			  </div>
			  <div class="form-group">
			  {!! Form::label('is_default',trans('messages.default'),['class' => 'control-label '])!!}
	                <div class="checkbox">
	                	<input name="is_default" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (isset($currency) && $currency->is_default) ? 'checked' : '' }} data-off-value="0">
	                </div>
			  </div>
			  {!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
			  	
