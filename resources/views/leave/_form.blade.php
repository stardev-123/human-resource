
		<div class="row">
			<div class="col-md-6">
			  <div class="form-group">
			    {!! Form::label('leave_type_id',trans('messages.leave').' '.trans('messages.type'),[])!!}
				{!! Form::select('leave_type_id', $leave_types,isset($leave) ? $leave->leave_type_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
				<label for="">{{trans('messages.duration')}}</label>
				<div class="input-daterange input-group">
				    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{isset($leave) ? $leave->from_date : ''}}" />
				    <span class="input-group-addon">{{trans('messages.to')}}</span>
				    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{isset($leave) ? $leave->to_date : ''}}" />
				</div>
			  </div>
			  @include('upload.index',['module' => 'leave','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($leave) ? $leave->id : ''])
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('description',trans('messages.description'),[])!!}
					{!! Form::textarea('description',isset($leave->description) ? $leave->description : '',['size' => '30x5', 'class' => 'form-control', 'placeholder' => trans('messages.description'),'data-height' => 100,"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
				</div>
			</div>
		</div>
		{{ getCustomFields('leave-form',$custom_field_values) }}
		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}