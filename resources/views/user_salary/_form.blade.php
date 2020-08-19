		<div class="form-group">
			<label for="date_range">{{trans('messages.date')}}</label>
			<div class="input-daterange input-group" id="datepicker">
			    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{isset($user_salary) ? $user_salary->from_date : ''}}" />
			    <span class="input-group-addon">{{trans('messages.to')}}</span>
			    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{isset($user_salary) ? $user_salary->to_date : ''}}"  />
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('currency_id',trans('messages.currency'),[])!!}
			{!! Form::select('currency_id', $currencies,isset($user_salary) ? $user_salary->currency_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
		</div>
		<div class="form-group">
			{!! Form::label('type',trans('messages.salary').' '.trans('messages.type'),['class' => ' control-label'])!!}
			{!! Form::select('type', [
			'hourly' => trans('messages.hourly').' '.trans('messages.salary'),
			'monthly' => trans('messages.monthly').' '.trans('messages.hourly'),
			],isset($user_salary) ? $user_salary->type : '' ,['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
		</div>
		<div class="form-group hourly_salary_field">
		    {!! Form::label('hourly_rate',trans('messages.hourly_rate'),[])!!}
			{!! Form::input('number','hourly_rate',isset($user_salary) ? currency($user_salary->hourly_rate) : '',['min'=>'0','class'=>'form-control','placeholder'=>trans('messages.hourly_rate')])!!}
		</div>
		<div class="monthly_salary_field">
			<div class="row">
				<div class="col-sm-6">
		  			<h6>({!! trans('messages.earning').' '.trans('messages.salary') !!})</h6>
		  			<div class="form-group">
					    {!! Form::label('overtime_hourly_rate',trans('messages.overtime').' '.trans('messages.hourly_rate'),[])!!}
						{!! Form::input('number','overtime_hourly_rate',isset($user_salary) ? currency($user_salary->overtime_hourly_rate) : '',['min'=>'0','class'=>'form-control','placeholder'=>trans('messages.overtime').' '.trans('messages.hourly_rate')])!!}
					</div>
				</div>
		  		<div class="col-sm-6">
		  			<h6>({!! trans('messages.deduction').' '.trans('messages.salary') !!})</h6>
		  			<div class="form-group">
					    {!! Form::label('late_hourly_rate',trans('messages.late').' '.trans('messages.hourly_rate'),[])!!}
						{!! Form::input('number','late_hourly_rate',isset($user_salary) ? currency($user_salary->late_hourly_rate) : '',['min'=>'0','class'=>'form-control','placeholder'=>trans('messages.late').' '.trans('messages.hourly_rate')])!!}
					</div>
		  			<div class="form-group">
					    {!! Form::label('early_leaving_hourly_rate',trans('messages.early_leaving').' '.trans('messages.hourly_rate'),[])!!}
						{!! Form::input('number','early_leaving_hourly_rate',isset($user_salary) ? currency($user_salary->early_leaving_hourly_rate) : '',['min'=>'0','class'=>'form-control','placeholder'=>trans('messages.early_leaving').' '.trans('messages.hourly_rate')])!!}
					</div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-sm-6">
				  	@foreach($earning_salary_heads as $earning_salary_head)
				  	<div class="form-group">
					    {!! Form::label('salary_head['.$earning_salary_head->id.']',$earning_salary_head->name,[])!!}
						{!! Form::input('number','salary_head['.$earning_salary_head->id.']',(isset($user_salary) && array_key_exists($earning_salary_head->id,$salary)) ? $salary[$earning_salary_head->id] : '0',['min'=>'0','class'=>'form-control','placeholder'=> trans('messages.amount')])!!}
					</div>
					@endforeach
				</div>
				<div class="col-sm-6">
				  	@foreach($deduction_salary_heads as $deduction_salary_head)
				  	<div class="form-group">
					    {!! Form::label('salary_head['.$deduction_salary_head->id.']',$deduction_salary_head->name,[])!!}
						{!! Form::input('number','salary_head['.$deduction_salary_head->id.']', (isset($user_salary) && array_key_exists($deduction_salary_head->id,$salary)) ? $salary[$deduction_salary_head->id] : '0',['min'=>'0','class'=>'form-control','placeholder'=> trans('messages.amount')])!!}
					</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('description',trans('messages.description'),[])!!}
			{!! Form::textarea('description',isset($user_salary) ? $user_salary->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			<span class="countdown"></span>
		</div>
		<div class="row">
		  	<div class="col-md-12">
				{{ getCustomFields('user-salary-form',isset($custom_user_salary_field_values) ? $custom_user_salary_field_values : []) }}
			</div>
		</div>
	  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
	  	<div class="clear"></div>