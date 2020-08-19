        @if(Auth::check() && !isset($job_detail))
            <div class="form-group">
                {!! Form::label('date_of_application',trans('messages.date_of').' '.trans('messages.application'),[])!!}
                <input type="text" class="form-control datepicker" name="date_of_application" placeholder="{{trans('messages.date_of').' '.trans('messages.application')}}" value="{{isset($job_application) ? $job_application->date_of_application : ''}}">
            </div>
            <div class="form-group">
                {!! Form::label('source',trans('messages.application').' '.trans('messages.source'),[])!!}
                {!! Form::select('source', config('lists.job_application_source'),isset($job_application) ? $job_application->source : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
            </div>
        @endif
        <div class="form-group">
            {!! Form::label('job_id',trans('messages.job').' '.trans('messages.title'),[])!!}
            {!! Form::select('job_id', $jobs,isset($job_application) ? $job_application->job_id : (isset($job) ? $job->id : ''),['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
        </div>
        @if(!isset($job_detail))
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('first_name',trans('messages.first').' '.trans('messages.name'),[])!!}
                        <input type="text" class="form-control text-input" name="first_name" placeholder="{{trans('messages.first').' '.trans('messages.name')}}" value="{{isset($job_application) ? $job_application->first_name : ''}}">
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('last_name',trans('messages.last').' '.trans('messages.name'),[])!!}
                        <input type="text" class="form-control text-input" name="last_name" placeholder="{{trans('messages.last').' '.trans('messages.name')}}" value="{{isset($job_application) ? $job_application->last_name : ''}}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('email',trans('messages.email'),[])!!}
                <input type="email" class="form-control text-input" name="email" placeholder="{{trans('messages.email')}}" value="{{isset($job_application) ? $job_application->email : ''}}">
            </div>
            <div class="row">
            	<div class="col-sm-6">
    		        <div class="form-group">
    		            {!! Form::label('gender',trans('messages.gender'),[])!!}
    		            <div class="checkbox">
    		            	<input type="radio" class="form-control icheck" name="gender" value="male" {{(isset($job_application) && $job_application->gender == 'male') ? 'checked' : (!isset($job_application) ? 'checked' : '')}}> {{trans('messages.male')}}
    		            	<input type="radio" class="form-control icheck" name="gender" value="female" {{(isset($job_application) && $job_application->gender == 'female') ? 'checked' : ''}}> {{trans('messages.female')}}
    		            </div>
    		        </div>
    		    </div>
            	<div class="col-sm-6">
                    <div class="form-group">
                		{!! Form::label('date_of_birth',trans('messages.date_of').' '.trans('messages.birth'),[])!!}
                        <input type="text" class="form-control datepicker" name="date_of_birth" placeholder="{{trans('messages.date_of').' '.trans('messages.birth')}}" value="{{isset($job_application) ? $job_application->date_of_birth : ''}}">
                    </div>
            	</div>
    		</div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        {!! Form::label('primary_contact_number',trans('messages.primary').' '.trans('messages.contact').' '.trans('messages.number'),[])!!}
                        <input type="text" class="form-control text-input" name="primary_contact_number" placeholder="{{trans('messages.primary').' '.trans('messages.contact').' '.trans('messages.number')}}" value="{{isset($job_application) ? $job_application->primary_contact_number : ''}}">
                    </div>
                    <div class="col-sm-6">
                        {!! Form::label('secondary_contact_number',trans('messages.secondary').' '.trans('messages.contact').' '.trans('messages.number'),[])!!}
                        <input type="text" class="form-control text-input" name="secondary_contact_number" placeholder="{{trans('messages.secondary').' '.trans('messages.contact').' '.trans('messages.number')}}" value="{{isset($job_application) ? $job_application->secondary_contact_number : ''}}">
                    </div>
                </div>
            </div>
    		<div class="form-group">
    		    {!! Form::label('address_line_1',trans('messages.address'),[])!!}
    			{!! Form::input('text','address_line_1',isset($job_application) ? $job_application->address_line_1 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_1')])!!}
    			<br />
    			{!! Form::input('text','address_line_2',isset($job_application) ? $job_application->address_line_2 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_2')])!!}
    			<br />
    			<div class="row">
    				<div class="col-xs-5">
    				{!! Form::input('text','city',isset($job_application) ? $job_application->city : '',['class'=>'form-control','placeholder'=>trans('messages.city')])!!}
    				</div>
    				<div class="col-xs-4">
    				{!! Form::input('text','state',isset($job_application) ? $job_application->state : '',['class'=>'form-control','placeholder'=>trans('messages.state')])!!}
    				</div>
    				<div class="col-xs-3">
    				{!! Form::input('text','zipcode',isset($job_application) ? $job_application->zipcode : '',['class'=>'form-control','placeholder'=>trans('messages.postcode')])!!}
    				</div>
    			</div>
    			<br />
    			{!! Form::select('country_id', config('country'),isset($job_application) ? $job_application->country_id : '',['class'=>'form-control show-tick','title'=>trans('messages.country')])!!}
    		</div>
        @else
            <p><strong>{{trans('messages.applying_as_user',['attribute' => Auth::user()->full_name])}}</strong></p>
        @endif
        <div class="form-group">
            {!! Form::label('additional_information',trans('messages.additional').' '.trans('messages.information'),[])!!}
            {!! Form::textarea('additional_information',isset($job_application) ? $job_application->additional_information : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.additional').' '.trans('messages.information'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
            <span class="countdown"></span>
        </div>
        @include('upload.index',['module' => 'job-application','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($job_application) ? $job_application->id : ''])
        {{ getCustomFields('job-application-form',$custom_field_values) }}
        {!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
