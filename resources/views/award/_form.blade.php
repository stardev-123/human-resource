
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('award_category_id',trans('messages.category'),[])!!}
					{!! Form::select('award_category_id',$award_categories,isset($award) ? $award->award_category_id : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
					@if(!isset($award))
						@include('global.create_entry',['module' => 'award-category'])
					@endif
				</div>
				<div class="form-group">
					{!! Form::label('user_id',trans('messages.user'),[])!!}
					{!! Form::select('user_id[]',$accessible_users,isset($award) ? $award->user()->pluck('user_id')->all() : '',['class'=>'form-control show-tick','title' => trans('messages.select_one'),'multiple' => 'multiple','data-actions-box' => "true"])!!}
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('date_of_award',trans('messages.date_of').' '.trans('messages.award'),[])!!}
							{!! Form::input('text','date_of_award',isset($award->date_of_award) ? $award->date_of_award : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.date_of').' '.trans('messages.award'),'readonly' => 'true'])!!}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('duration',trans('messages.duration'),[])!!}
							{!! Form::select('duration',[
								'monthly' => trans('messages.monthly'),
								'yearly' => trans('messages.yearly'),
								'period' => trans('messages.period')
							],isset($award) ? $award->duration : '',['id' => 'award-duration','class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
						</div>
					</div>
				</div>
				<div class="award-monthly-field">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('month',trans('messages.month'),[])!!}
								{!! Form::select('month',$months,isset($award) ? $award->month : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('year',trans('messages.year'),[])!!}
								{!! Form::select('year',$years,isset($award) ? $award->year : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
							</div>
						</div>
					</div>
				</div>
				<div class="award-yearly-field">
					<div class="form-group">
						{!! Form::label('year',trans('messages.year'),[])!!}
						{!! Form::select('year',$years,isset($award) ? $award->year : '',['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
					</div>
				</div>
				<div class="award-period-field">
					<div class="form-group">
						<label for="">{{trans('messages.period')}}</label>
						<div class="input-daterange input-group">
						    <input type="text" class="input-sm form-control" name="from_date" readonly />
						    <span class="input-group-addon">{{trans('messages.to')}}</span>
						    <input type="text" class="input-sm form-control" name="to_date" readonly />
						</div>
					</div>
				</div>
				@include('upload.index',['module' => 'award','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($award) ? $award->id : ''])
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('description',trans('messages.description'),[])!!}
					{!! Form::textarea('description',isset($award->description) ? $award->description : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description'),'data-height' => 100])!!}
				</div>
			</div>
		</div>
		{{ getCustomFields('award-form',$custom_field_values) }}
		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}