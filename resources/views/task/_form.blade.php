	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			    {!! Form::label('title',trans('messages.title'),[])!!}
				{!! Form::input('text','title',isset($task->title) ? $task->title : '',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					    {!! Form::label('task_priority_id',trans('messages.priority'),[])!!}
						{!! Form::select('task_priority_id', $task_priorities,isset($task->task_priority_id) ? $task->task_priority_id : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'id' => 'task_priority_id'])!!}
						@if(!isset($task))
							@include('global.create_entry',['module' => 'task-priority'])
						@endif
				  	</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
					    {!! Form::label('task_category_id',trans('messages.category'),[])!!}
						{!! Form::select('task_category_id', $task_categories,isset($task->task_category_id) ? $task->task_category_id : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'id' => 'task_category_id'])!!}
						@if(!isset($task))
							@include('global.create_entry',['module' => 'task-category'])
						@endif
				  	</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
					{!! Form::label('start_date',trans('messages.start').' '.trans('messages.date'),[])!!}
					{!! Form::input('text','start_date',isset($task->start_date) ? $task->start_date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.start').' '.trans('messages.date'),'readonly' => 'true'])!!}
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						{!! Form::label('due_date',trans('messages.due').' '.trans('messages.date'),[])!!}
						{!! Form::input('text','due_date',isset($task->due_date) ? $task->due_date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.due').' '.trans('messages.date'),'readonly' => 'true'])!!}
					</div>
				</div>
			</div>
			<div class="form-group">
			    {!! Form::label('user',trans('messages.user'),[])!!}
				{!! Form::select('user_id[]', $users,isset($selected_user) ? $selected_user : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
			</div>
			<div class="form-group">
				{!! Form::label('tags',trans('messages.tags'),[])!!}
				{!! Form::input('text','tags',isset($task->tags) ? $task->tags : '',['class'=>'form-control','placeholder'=>trans('messages.tags'),'data-role' => 'tagsinput'])!!}
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label('description',trans('messages.description'),[])!!}
				{!! Form::textarea('description',isset($task->description) ? $task->description : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description'),'data-height' => 100])!!}
			</div>
			@include('upload.index',['module' => 'task','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($task) ? $task->id : ''])
		</div>
	</div>
	{{ getCustomFields('task-form',$custom_field_values) }}
	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}