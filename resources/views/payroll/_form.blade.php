		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('type',trans('messages.payment').' '.trans('messages.type'),['class' => ' control-label'])!!}
					{!! Form::select('type', [
					'hourly' => trans('messages.hourly').' '.trans('messages.total'),
					'monthly' => trans('messages.monthly').' '.trans('messages.total'),
					],isset($user_salary) ? $user_salary->type : '' ,['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('date_of_payroll',trans('messages.date_of').' '.trans('messages.payroll'),[])!!}
					{!! Form::input('text','date_of_payroll',isset($payroll) ? $payroll->date_of_payroll : date('Y-m-d'),['class'=>'form-control datepicker','placeholder'=>trans('messages.date_of').' '.trans('messages.payroll')])!!}
				</div>
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('',$user_salary->Currency->detail,['class' => ' control-label'])!!}
		</div>
		<div class="form-group hourly_salary_field">
		    {!! Form::label('hourly',trans('messages.hourly').' '.trans('messages.total'),[])!!}
			{!! Form::input('text','hourly',isset($hourly) ? $hourly : '',['class'=>'form-control','placeholder'=>trans('messages.hourly').' '.trans('messages.total')])!!}
		</div>
		<div class="monthly_salary_field">
			<div class="row">
				<div class="col-sm-6">
		  			<h6>({!! trans('messages.earning').' '.trans('messages.payment') !!})</h6>
		  			<div class="form-group">
					    {!! Form::label('overtime',trans('messages.overtime').' '.trans('messages.pay'),[])!!}
						{!! Form::input('text','overtime',isset($overtime) ? $overtime : '',['class'=>'form-control','placeholder'=>trans('messages.overtime').' '.trans('messages.pay')])!!}
					</div>
				</div>
		  		<div class="col-sm-6">
		  			<h6>({!! trans('messages.payment').' '.trans('messages.deduction') !!})</h6>
		  			<div class="form-group">
					    {!! Form::label('late',trans('messages.total').' '.trans('.messages.late').' '.trans('messages.deduction'),[])!!}
						{!! Form::input('text','late',isset($late) ? $late : '',['class'=>'form-control','placeholder'=>trans('messages.late').' '.trans('messages.deduction')])!!}
					</div>
		  			<div class="form-group">
					    {!! Form::label('early_leaving',trans('messages.total').' '.trans('messages.early_leaving').' '.trans('messages.deduction'),[])!!}
						{!! Form::input('text','early_leaving',isset($early_leaving) ? $early_leaving : '',['class'=>'form-control','placeholder'=>trans('messages.early_leaving').' '.trans('messages.deduction')])!!}
					</div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-sm-6">
				  	@foreach($earning_salary_heads as $earning_salary_head)
				  	<div class="form-group">
					    {!! Form::label('salary_head['.$earning_salary_head->id.']',$earning_salary_head->name,[])!!}
						{!! Form::input('text','salary_head['.$earning_salary_head->id.']',(isset($salary_values) && array_key_exists($earning_salary_head->id,$salary_values)) ? $salary_values[$earning_salary_head->id] : '0',['class'=>'form-control','placeholder'=> trans('messages.amount')])!!}
					</div>
					@endforeach
				</div>
				<div class="col-sm-6">
				  	@foreach($deduction_salary_heads as $deduction_salary_head)
				  	<div class="form-group">
					    {!! Form::label('salary_head['.$deduction_salary_head->id.']',$deduction_salary_head->name,[])!!}
						{!! Form::input('text','salary_head['.$deduction_salary_head->id.']', (isset($salary_values) && array_key_exists($deduction_salary_head->id,$salary_values)) ? $salary_values[$deduction_salary_head->id] : '0',['class'=>'form-control','placeholder'=> trans('messages.amount')])!!}
					</div>
					@endforeach
				</div>
			</div>
		</div>
		<hr />
		{{ getCustomFields('payroll-form',$custom_field_values) }}
		{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
