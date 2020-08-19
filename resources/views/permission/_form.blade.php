			<div class="form-group">
			    {!! Form::label('name',trans('messages.permission').' '.trans('messages.name'),[])!!}
				{!! Form::input('text','name',isset($permission->name) ? toWord($permission->name) : '',['class'=>'form-control','placeholder'=>trans('messages.permission').' '.trans('messages.name')])!!}
			</div>
			<div class="form-group">
			    {!! Form::label('category',trans('messages.permission').' '.trans('messages.category'),[])!!}
				{!! Form::input('text','category',isset($permission->category) ? toWord($permission->category) : '',['class'=>'form-control','placeholder'=>trans('messages.permission').' '.trans('messages.category')])!!}
			</div>
			<div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($permission->description) ? $permission->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,'data-autoresize' => 1])!!}
			    <span class="countdown"></span>
			</div>
			{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}