		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('location_id',trans('messages.location'),[])!!}
					{!! Form::select('location_id', $locations,isset($user_location) ? $user_location->location_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="date_range">{{trans('messages.date')}}</label>
					<div class="input-daterange input-group" id="datepicker">
					    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{isset($user_location) ? $user_location->from_date : ''}}" />
					    <span class="input-group-addon">{{trans('messages.to')}}</span>
					    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{isset($user_location) ? $user_location->to_date : ''}}"  />
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('description',trans('messages.description'),[])!!}
			{!! Form::textarea('description',isset($user_location) ? $user_location->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			<span class="countdown"></span>
		</div>
		<div class="row">
			<div class="col-md-12">
				{{ getCustomFields('user-location-form',isset($custom_user_location_field_values) ? $custom_user_location_field_values : []) }}
			</div>
		</div>
	    {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		<div class="clear"></div>