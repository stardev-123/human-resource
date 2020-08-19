<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			{!! Form::label('name',trans('messages.name'),[])!!}
			<div class="row">
				<div class="col-md-6">
					{!! Form::input('text','first_name',isset($client->first_name) ? $client->first_name : '',['class'=>'form-control','placeholder'=>trans('messages.first').' '.trans('messages.name')])!!}
				</div>
				<div class="col-md-6">
					{!! Form::input('text','last_name',isset($client->last_name) ? $client->last_name : '',['class'=>'form-control','placeholder'=>trans('messages.last').' '.trans('messages.name')])!!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					{!! Form::label('gender',trans('messages.gender'),[])!!}
					<div class="checkbox">
						<input type="radio" class="form-control icheck" name="gender" value="male" {{isset($client->gender) && ($client->gender == 'male') ? 'checked' : ''}}> {{trans('messages.male')}}
						<input type="radio" class="form-control icheck" name="gender" value="female"  {{isset($client->gender) && ($client->gender == 'female') ? 'checked' : ''}}> {{trans('messages.female')}}
						<input type="radio" class="form-control icheck" name="gender" value="other"  {{isset($client->gender) && ($client->gender == 'other') ? 'checked' : ''}}> {{trans('messages.other')}}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label(trans('messages.phone'))!!}
					{!! Form::input('text','phone',isset($client->phone) ? $client->phone : '',['class'=>'form-control','placeholder'=>trans('messages.home').' '.trans('messages.phone')])!!}
				</div>
				<div class="form-group">
					{!! Form::label(trans('messages.email'))!!}
					{!! Form::input('text','email',isset($client->email) ? $client->email : '',['class'=>'form-control','placeholder'=>trans('messages.email')])!!}
				</div>
				<div class="form-group">
					{!! Form::label(trans('messages.note'),[])!!}
					{!! Form::textarea('note',isset($client->note) ? $client->note : '',['size' => '30x15', 'class' => 'form-control summernote', 'placeholder' => trans('messages.note'),'data-height' => 100])!!}
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('date_of_birth',trans('messages.date_of').' '.trans('messages.birth'),[])!!}
					{!! Form::input('text','date_of_birth',isset($client->date_of_birth) ? $client->date_of_birth : '',['class'=>'form-control datepicker','readonly' => null,'placeholder'=>trans('messages.date_of').' '.trans('messages.birth')])!!}
				</div>
				<div class="form-group">
					{!! Form::label('address',trans('messages.address'),[])!!}
					{!! Form::input('text','address_line_1',isset($client->address_line_1) ? $client->address_line_1 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_1')])!!}
					<br />
					{!! Form::input('text','address_line_2',isset($client->address_line_2) ? $client->address_line_2 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_2')])!!}
					<br />
					<div class="row">
						<div class="col-xs-5">
							{!! Form::input('text','city',isset($client->city) ? $client->city : '',['class'=>'form-control','placeholder'=>trans('messages.city')])!!}
						</div>
						<div class="col-xs-4">
							{!! Form::input('text','state',isset($client->state) ? $client->state : '',['class'=>'form-control','placeholder'=>trans('messages.state')])!!}
						</div>
						<div class="col-xs-3">
							{!! Form::input('text','zipcode',isset($client->zipcode) ? $client->zipcode : '',['class'=>'form-control','placeholder'=>trans('messages.postcode')])!!}
						</div>
					</div>
					<br />
					{!! Form::select('country_id', [null => trans('messages.select_one')] + config('country'),isset($client->country_id) ? $client->country_id : '',['class'=>'form-control show-tick','title'=>trans('messages.country')])!!}
				</div>
				@include('upload.index',['module' => 'client','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($client) ? $client->id : ''])
			{{ getCustomFields('client-form',$custom_field_values) }}
			{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
			</div>
		</div>
	</div>
</div>
