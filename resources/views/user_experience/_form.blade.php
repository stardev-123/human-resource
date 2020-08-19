		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('company_name',trans('messages.company').' '.trans('messages.name'))!!}
					{!! Form::input('text','company_name',isset($user_experience) ? $user_experience->company_name : '',['class'=>'form-control','placeholder'=>trans('messages.company').' '.trans('messages.name')])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('company_address',trans('messages.company').' '.trans('messages.address'))!!}
					{!! Form::textarea('company_address',isset($user_experience) ? $user_experience->company_address : '',['size' => '30x1', 'class' => 'form-control', 'placeholder' => trans('messages.company').' '.trans('messages.address'),'data-autoresize' => 1])!!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('company_contact_number',trans('messages.contact').' '.trans('messages.number'))!!}
					{!! Form::input('text','company_contact_number',isset($user_experience) ? $user_experience->company_contact_number : '',['class'=>'form-control','placeholder'=>trans('messages.contact').' '.trans('messages.number')])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
				    {!! Form::label('company_website',trans('messages.company').' '.trans('messages.website'))!!}
					{!! Form::input('text','company_website',isset($user_experience) ? $user_experience->company_website : '',['class'=>'form-control','placeholder'=>trans('messages.company').' '.trans('messages.website')])!!}
				</div>
			</div>
		</div>
		<div class="form-group">
		    {!! Form::label('job_title',trans('messages.job').' '.trans('messages.title'))!!}
			{!! Form::input('text','job_title',isset($user_experience) ? $user_experience->job_title : '',['class'=>'form-control','placeholder'=>trans('messages.job').' '.trans('messages.title')])!!}
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="date_range">{{trans('messages.date')}}</label>
					<div class="input-daterange input-group" id="datepicker">
					    <input type="text" class="input-sm form-control" name="from_date" readonly value="{{isset($user_experience) ? $user_experience->from_date : ''}}" />
					    <span class="input-group-addon">{{trans('messages.to')}}</span>
					    <input type="text" class="input-sm form-control" name="to_date" readonly value="{{isset($user_experience) ? $user_experience->to_date : ''}}"  />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				@include('upload.index',['module' => 'user-experience','upload_button' => trans('messages.upload').' '.trans('messages.experience'),'module_id' => isset($user_experience) ? $user_experience->id : ''])
			</div>
			<div class="col-md-12">
				<div class="form-group">
					{!! Form::label('description',trans('messages.description'),[])!!}
					{!! Form::textarea('description',isset($user_experience) ? $user_experience->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
				<span class="countdown"></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				{{ getCustomFields('user-experience-form',isset($custom_user_experience_field_values) ? $custom_user_experience_field_values : []) }}
			</div>
		</div>
	    {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
		<div class="clear"></div>