
		<div class="row">
			<div class="col-md-6">
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('expense_head_id',trans('messages.expense').' '.trans('messages.head'),[])!!}
					{!! Form::select('expense_head_id', $expense_heads,isset($expense) ? $expense->expense_head_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
				  </div>
				</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('date_of_expense',trans('messages.date_of').' '.trans('messages.expense'),[])!!}
					{!! Form::input('text','date_of_expense',isset($expense) ? $expense->date_of_expense : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.amount')])!!}
				  </div>
			  	</div>
			  </div>
			  <div class="row">
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('currency_id',trans('messages.currency'),[])!!}
					{!! Form::select('currency_id', $currencies,isset($expense) ? $expense->currency_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
				  </div>
			  	</div>
			  	<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('amount',trans('messages.amount'),[])!!}
					{!! Form::input('text','amount',isset($expense) ? $expense->amount : '',['class'=>'form-control','placeholder'=>trans('messages.amount')])!!}
				  </div>
			  	</div>
			  </div>
				@include('upload.index',['module' => 'expense','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($expense) ? $expense->id : ''])
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('description',trans('messages.description'),[])!!}
					{!! Form::textarea('description',isset($expense->description) ? $expense->description : '',['size' => '30x5', 'class' => 'form-control', 'placeholder' => trans('messages.description'),'data-height' => 100,"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
			    	<span class="countdown"></span>
				</div>
			</div>
		</div>
		{{ getCustomFields('expense-form',$custom_field_values) }}
		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}