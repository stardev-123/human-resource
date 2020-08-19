									<div class="col-sm-6">
										<div class="form-group">
									    {!! Form::label('relation',trans('messages.relation'))!!}
										{!! Form::select('relation', $user_relation,isset($user_contact) ? $user_contact->relation : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
										</div>
										<div class="checkbox">
											<label>
											  {!! Form::checkbox('is_primary', 1,(isset($user_contact) && $user_contact->is_primary) ? 'checked' : '',['class' => 'icheck']) !!} {!! trans('messages.primary').' '.trans('messages.contact') !!}
											</label>
											<label>
											  {!! Form::checkbox('is_dependent', 1,(isset($user_contact) && $user_contact->is_dependent) ? 'checked' : '',['class' => 'icheck']) !!} {!! trans('messages.dependent') !!}
											</label>
										</div>
										<div class="form-group">
									    {!! Form::label('name',trans('messages.name'))!!}
										{!! Form::input('text','name',isset($user_contact) ? $user_contact->name : '',['class'=>'form-control','placeholder'=>trans('messages.name')])!!}
										</div>
										<div class="form-group">
									    {!! Form::label('work_phone',trans('messages.phone'))!!}
										<div class="row">
											<div class="col-xs-8">
											{!! Form::input('text','work_phone',isset($user_contact) ? $user_contact->work_phone : '',['class'=>'form-control','placeholder'=>trans('messages.work')])!!}
											</div>
											<div class="col-xs-4">
											{!! Form::input('text','work_phone_extension',isset($user_contact) ? $user_contact->work_phone_extension : '',['class'=>'form-control','placeholder'=>trans('messages.ext')])!!}
											</div>
										</div>
										<br />
										{!! Form::input('text','mobile',isset($user_contact) ? $user_contact->mobile : '',['class'=>'form-control','placeholder'=>trans('messages.mobile')])!!}
										<br />
										{!! Form::input('text','home',isset($user_contact) ? $user_contact->home : '',['class'=>'form-control','placeholder'=>trans('messages.home')])!!}
									</div>
								</div>
								<div class="col-sm-6">
			    				  	<div class="form-group">
									    {!! Form::label('email',trans('messages.email'))!!}
										{!! Form::input('text','work_email',isset($user_contact) ? $user_contact->work_email : '',['class'=>'form-control','placeholder'=>trans('messages.work')])!!}
										<br />
										{!! Form::input('text','personal_email',isset($user_contact) ? $user_contact->personal_email : '',['class'=>'form-control','placeholder'=>trans('messages.personal')])!!}
									</div>
									<div class="form-group">
									    {!! Form::label('address',trans('messages.address'),[])!!}
										{!! Form::input('text','address_line_1',isset($user_contact) ? $user_contact->address_line_1 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_1')])!!}
										<br />
										{!! Form::input('text','address_line_2',isset($user_contact) ? $user_contact->address_line_2 : '',['class'=>'form-control','placeholder'=>trans('messages.address_line_2')])!!}
										<br />
										<div class="row">
											<div class="col-xs-5">
											{!! Form::input('text','city',isset($user_contact) ? $user_contact->city : '',['class'=>'form-control','placeholder'=>trans('messages.city')])!!}
											</div>
											<div class="col-xs-4">
											{!! Form::input('text','state',isset($user_contact) ? $user_contact->state : '',['class'=>'form-control','placeholder'=>trans('messages.state')])!!}
											</div>
											<div class="col-xs-3">
											{!! Form::input('text','zipcode',isset($user_contact) ? $user_contact->zipcode : '',['class'=>'form-control','placeholder'=>trans('messages.postcode')])!!}
											</div>
										</div>
										<br />
										{!! Form::select('country_id', config('country'),isset($user_contact) ? $user_contact->country_id : '',['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
									</div>
									{!! Form::hidden('type','contact') !!}
								</div>
								<div class="col-md-12">
									{{ getCustomFields('user-contact-form',isset($custom_user_contact_field_values) ? $custom_user_contact_field_values : []) }}
								</div>
                                {!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
								<div class="clear"></div>
