			<div class="form-group">
				<label for="date_range">{{trans('messages.date')}}</label>
				<div class="input-daterange input-group" id="datepicker">
				    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{isset($user_leave) ? $user_leave->from_date : ''}}" />
				    <span class="input-group-addon">{{trans('messages.to')}}</span>
				    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{isset($user_leave) ? $user_leave->to_date : ''}}"  />
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('description',trans('messages.description'),[])!!}
				{!! Form::textarea('description',isset($user_leave) ? $user_leave->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
				<span class="countdown"></span>
			</div>
			@foreach($leave_types->chunk(3) as $chunk)
		  		<div class="row">
    				@foreach ($chunk as $leave_type)
    				<div class="col-sm-4">
					  	<div class="form-group">
							<label for="to_date">{!! $leave_type->name !!}</label>
							<input type="number" class="form-control" name="leave_type[{!! $leave_type->id !!}]" placeholder="{!! $leave_type->leave_name !!}" value="{{ (isset($user_leave) && array_key_exists($leave_type->id,$user_leave_details)) ? $user_leave_details[$leave_type->id] : 0}}" required min="0">
					  	</div>
				  	</div>
				  	@endforeach
			  	</div>
		  	@endforeach
		  	<div class="row">
			  	<div class="col-md-12">
					{{ getCustomFields('user-leave-form',isset($custom_user_leave_field_values) ? $custom_user_leave_field_values : []) }}
				</div>
			</div>
		  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		  	<div class="clear"></div>