
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.edit').' '.trans('messages.attendance') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($clock,['method' => 'POST','route' => ['update-clock',$clock->user_id,$clock->date,$clock->id] ,'class' => '','id' => 'update-clock-in-out','data-table-refresh' => 'clock-list-table']) !!}
			<div class="form-group row">
		  	  <div class="col-md-4">
		  	  {!! Form::label('clock_in',trans('messages.clock_in'),['class' => 'control-label'])!!}
			  {!! Form::input('text','clock_in',isset($clock->clock_in) ? date('Y-m-d h:i A',strtotime($clock->clock_in)) : '',['class'=>'form-control datetimepicker','readonly' => true])!!}
			  </div>
			  <div class="col-md-4">
			  {!! Form::label('clock_out',trans('messages.clock_out'),['class' => 'control-label'])!!}
			  {!! Form::input('text','clock_out',isset($clock->clock_out) ? date('Y-m-d h:i A',strtotime($clock->clock_out)) : '',['class'=>'form-control datetimepicker','readonly' => true])!!}
			  </div>
			</div>
			{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary']) !!}
		{!! Form::close() !!}
	</div>
