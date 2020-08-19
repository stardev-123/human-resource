			<div class="col-sm-6">
			  <div class="form-group">
			    {!! Form::label('nexmo_api_key','Nexmo API Key',[])!!}
				{!! Form::input('text','nexmo_api_key',config('config.nexmo_api_key') ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'API Key'])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('nexmo_api_secret','Nexmo API Secret',[])!!}
				{!! Form::input('text','nexmo_api_secret',config('config.nexmo_api_secret') ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'API Secret'])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('nexmo_from_number','Nexmo From Number',[])!!}
				{!! Form::input('text','nexmo_from_number',config('config.nexmo_from_number') ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'From Number'])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('your_number','Your Mobile Number',[])!!}
				{!! Form::input('text','your_number',config('config.your_number') ? config('config.hidden_value') : '',['class'=>'form-control','placeholder'=>'Your Number'])!!}
			  </div>
			<input type="hidden" name="config_type" class="hidden_fields" value="sms">
			{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary']) !!}
			</div>
			<div class="clear"></div>
