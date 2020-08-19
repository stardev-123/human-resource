
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.sub').' '.trans('messages.task') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($sub_task,['method' => 'PATCH','route' => ['sub-task.update',$sub_task] ,'class' => 'sub-task-edit-form','id' => 'sub-task-edit-form','data-table-refresh' => 'sub-task-table']) !!}
			<div class="form-group">
				{!! Form::label('title',trans('messages.title'),[])!!}
				{!! Form::input('text','title',$sub_task->title,['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
			</div>
		  <div class="form-group">
		    {!! Form::textarea('description',$sub_task->description,['size' => '30x3', 'class' => 'form-control ', 'placeholder' => trans('messages.description'),'data-autoresize' => 1,"data-show-counter" => 1,"data-limit" => config('config.textarea_limit')])!!}
		    <span class="countdown"></span>
		  </div>
		  <div class="form-group">
		  	{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right btn-sm']) !!}
		  </div>
		{!! Form::close() !!}
		<div class="clear"></div>
	</div>