
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.template') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($template,['method' => 'PATCH','route' => ['template.update',$template] ,'class' => 'email-template-form','id' => 'email-template-form-edit','data-form-table' => 'template_table']) !!}
			<div class="form-group">
		    {!! Form::label('subject',trans('messages.subject'),[])!!}
			{!! Form::input('text','subject',isset($template->subject) ? $template->subject : '',['class'=>'form-control','placeholder'=>trans('messages.subject')])!!}
		  </div>
		  <div class="form-group">
		    {!! Form::label('body',trans('messages.body'),[])!!}
		    {!! Form::textarea('body',isset($template->body) ? $template->body : '',['size' => '30x3', 'class' => 'form-control summernote', 'placeholder' => trans('messages.body'),'data-height' => '350'])!!}
		  	<div class="help-block"><strong>{!! trans('messages.available').' '.trans('messages.field') !!}</strong> : {!! ($template->is_default && config('template.'.$template->slug.'.fields')) ? config('template.'.$template->slug.'.fields') : config('template-field.'.$template->category) !!} <br /> {{ trans('messages.template_field_instruction') }}</div>
		  </div>
		  <div class="form-group">
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		  </div>
		{!! Form::close() !!}
		<div class="clearfix"></div>
	</div>