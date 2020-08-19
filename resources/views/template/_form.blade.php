		  <div class="form-group">
		    {!! Form::label('category',trans('messages.category'),['class' => 'control-label'])!!}
		    {!! Form::select('category', $category, '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
		  </div>
		  <div class="form-group">
		    {!! Form::label('name',trans('messages.name'),[])!!}
			{!! Form::input('text','name','',['class'=>'form-control','placeholder'=>trans('messages.name')])!!}
		  </div>
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}