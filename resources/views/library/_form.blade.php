	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			    {!! Form::label('title',trans('messages.title'),[])!!}
				{!! Form::input('text','title',isset($library->title) ? $library->title : '',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label('description',trans('messages.description'),[])!!}
				{!! Form::textarea('description',isset($library->description) ? $library->description : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description'),'data-height' => 100])!!}
			</div>
			@include('upload.index',['module' => 'library','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($library) ? $library->id : ''])
		</div>
	</div>
	{{ getCustomFields('library-form',$custom_field_values) }}
	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}