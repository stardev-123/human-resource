
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.user').' '.trans('messages.task') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($task,['method' => 'POST','route' => ['task.store-rating',$task->id,$user->id] ,'class' => 'task-rating-form','id' => 'task-rating-form']) !!}
		  	<div class="row">
		  		<div class="col-md-6">
				  	<div class="form-group">
					    {!! Form::label('user_id',trans('messages.user'),[])!!}
					    <p>{{$user->name_with_designation_and_department}}</p>
				    </div>
		  		</div>
		  		<div class="col-md-6">
				    <div class="form-group">
					    {!! Form::label('rating',trans('messages.rating'),[])!!}
					    {!! Form::select('rating', ['1' => '1 Star','2' => '2 Star','3' => '3 Star','4' => '4 Star','5' => '5 Star']
					    	, $user_rating,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
				    </div>
		  		</div>
		  	</div>
			<div class="form-group">
				{!! Form::label('comment',trans('messages.comment'),[])!!}
				{!! Form::textarea('comment',$user_comment,['size' => '30x6', 'class' => 'form-control', 'placeholder' => trans('messages.comment'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1,'size' => '30x3'])!!}
				<span class="countdown"></span>
			</div>
		    {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		  {!! Form::close() !!}
		<div class="clear"></div>
	</div>