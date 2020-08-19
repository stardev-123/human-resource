
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.template') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::open(['route' => 'template.store','role' => 'form', 'class'=>'template-form','id' => 'template-form']) !!}
		  <div class="form-group">
		    {!! Form::label('category',trans('messages.category'),['class' => 'control-label'])!!}
		    {!! Form::select('category', $category, '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
		  </div>
		  <div class="form-group">
		    {!! Form::label('subject',trans('messages.subject'),[])!!}
			{!! Form::input('text','subject','',['class'=>'form-control','placeholder'=>trans('messages.subject')])!!}
		  </div>
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.add'),['class' => 'btn btn-primary']) !!}
		{!! Form::close() !!}
	</div>
