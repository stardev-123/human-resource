	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			    {!! Form::label('title',trans('messages.title'),[])!!}
				{!! Form::input('text','title',isset($announcement->title) ? $announcement->title : '',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
			</div>
			<div class="form-group">
			    {!! Form::label('duration',trans('messages.duration'),[])!!}
				<div class="input-daterange input-group">
				    <input type="text" class="input-sm form-control" name="from_date" value="{{isset($announcement->from_date) ? $announcement->from_date : ''}}" readonly />
				    <span class="input-group-addon">{{trans('messages.to')}}</span>
				    <input type="text" class="input-sm form-control" name="to_date"  value="{{isset($announcement->to_date) ? $announcement->to_date : ''}}" readonly />
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('audience',trans('messages.audience'),[])!!}
				{!! Form::select('audience',['user' => trans('messages.user'),'designation' => trans('messages.designation')],isset($announcement) ? $announcement->audience : '',['id' => 'announcement-audience','class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
			</div>
			<div class="announcement-audience-user">
				<div class="form-group">
					{!! Form::label('user_id',trans('messages.user'),[])!!}
					{!! Form::select('user_id[]',$accessible_users,isset($announcement) ? $announcement->user()->pluck('user_id')->all() : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
				</div>
			</div>
			<div class="announcement-audience-designation">
				<div class="form-group">
					{!! Form::label('designation_id',trans('messages.designation'),[])!!}
					{!! Form::select('designation_id[]',$accessible_designations,isset($announcement) ? $announcement->designation()->pluck('designation_id')->all() : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label('description',trans('messages.description'),[])!!}
				{!! Form::textarea('description',isset($announcement->description) ? $announcement->description : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description'),'data-height' => 100])!!}
			</div>
			@include('upload.index',['module' => 'announcement','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($announcement) ? $announcement->id : ''])
		</div>
	</div>
	{{ getCustomFields('announcement-form',$custom_field_values) }}
	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}