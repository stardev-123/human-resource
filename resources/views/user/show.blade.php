@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
			<li><a href="/home">{!! trans('messages.home') !!}</a></li>
			<li><a href="/user">{!! trans('messages.user') !!}</a></li>
			<li class="active">{!! $user->name_with_designation_and_department !!}</li>
		</ul>
	@stop

	@section('content')
		<div class="row">
			<div id="load-user-detail" data-extra="&user_id={{$user->id}}" data-source="/user/detail"></div>
			<div class="col-sm-12">
				<div class="box-info full">
					<div class="tabs-left">
						<ul class="nav nav-tabs col-md-2 tab-list" style="padding-right:0;">
						  <li><a href="#basic-tab" data-toggle="tab"> {{ trans('messages.basic') }} </a></li>
						  @if(!$user->hasRole(DEFAULT_ROLE))
						  	<li><a href="#employment-tab" data-toggle="tab"> {{ trans('messages.employment') }} </a></li>
							<li><a href="#designation-tab" data-toggle="tab"> {{ trans('messages.designation') }} </a></li>
						  @endif
						  @if(Entrust::can('edit-user'))
							  <li><a href="#avatar-tab" data-toggle="tab"> {{ trans('messages.avatar') }} </a></li>
							  <li><a href="#social-tab" data-toggle="tab"> {{ trans('messages.social') }} </a></li>
						  @endif
						  <li><a href="#contact-tab" data-toggle="tab"> {{ trans('messages.contact') }} </a></li>
						  <li><a href="#bank-account-tab" data-toggle="tab"> {{ trans('messages.account') }} </a></li>
						  <li><a href="#document-tab" data-toggle="tab"> {{ trans('messages.document') }} </a></li>
						  <li><a href="#location-tab" data-toggle="tab"> {{ trans('messages.location') }} </a></li>
						  <li><a href="#contract-tab" data-toggle="tab"> {{ trans('messages.contract') }} </a></li>
						  <li><a href="#shift-tab" data-toggle="tab"> {{ trans('messages.shift') }} </a></li>
						  <li><a href="#leave-tab" data-toggle="tab"> {{ trans('messages.leave') }} </a></li>
						  <li><a href="#salary-tab" data-toggle="tab"> {{ trans('messages.salary') }} </a></li>
						  <li><a href="#qualification-tab" data-toggle="tab"> {{ trans('messages.qualification') }} </a></li>
						  <li><a href="#experience-tab" data-toggle="tab"> {{ trans('messages.experience') }} </a></li>
						  @if($user->id != Auth::user()->id && Entrust::can('reset-user-password'))
							<li><a href="#reset-password-tab" data-toggle="tab"> {{ trans('messages.reset').' '.trans('messages.password') }} </a></li>
						  @endif
						  @if(config('config.enable_email_template') && Entrust::can('email-user'))
							<li><a href="#email-tab" data-toggle="tab">{{trans('messages.email')}}</a>
							</li>
						  @endif
						</ul>
						<div class="tab-content col-md-10 col-xs-10" style="padding:0px 25px 10px 25px;">
						  <div class="tab-pane animated fadeInRight" id="basic-tab">
							<div class="user-profile-content-wm">
								<h2><strong>{{ trans('messages.basic') }} </strong></h2>
								@if(Entrust::can('edit-user'))
									{!! Form::model($user,['method' => 'POST','route' => ['user.profile-update',$user->id] ,'class' => 'user-profile-form','id' => 'user-profile-form','data-no-form-clear' => 1,'data-refresh' => 'load-user-detail']) !!}
										<div class="row">
											<div class="col-md-6">
												@if(!$user->hasRole(DEFAULT_ROLE))
													<div class="form-group">
														{!! Form::label('role_id',trans('messages.role'),[])!!}
														{!! Form::select('role_id[]',$roles,$user->Roles->pluck('id')->all(),['class'=>'form-control show-tick'])!!}
													</div>
												@endif
												<div class="form-group">
													{!! Form::label('employee_code',trans('messages.user').' '.trans('messages.code'),[])!!}
													{!! Form::input('text','employee_code',$user->Profile->employee_code,['class'=>'form-control','placeholder'=>trans('messages.user').' '.trans('messages.code')])!!}
												</div>
											  <div class="form-group">
												{!! Form::label('name',trans('messages.name'),[])!!}
												<div class="row">
													<div class="col-md-6">
													{!! Form::input('text','first_name',$user->Profile->first_name,['class'=>'form-control','placeholder'=>trans('messages.first').' '.trans('messages.name')])!!}
													</div>
													<div class="col-md-6">
													{!! Form::input('text','last_name',$user->Profile->last_name,['class'=>'form-control','placeholder'=>trans('messages.last').' '.trans('messages.name')])!!}
													</div>
												</div>
											  </div>
												<div class="row">
													<div class="col-xs-6">
													  <div class="form-group">
															{!! Form::label('gender',trans('messages.gender'),[])!!}
															<div class="checkbox">
																<input type="radio" class="form-control icheck" name="gender" value="male" {{($user->Profile->gender == 'male') ? 'checked' : ''}}> {{trans('messages.male')}}
																<input type="radio" class="form-control icheck" name="gender" value="female"  {{($user->Profile->gender == 'female') ? 'checked' : ''}}> {{trans('messages.female')}}
																<input type="radio" class="form-control icheck" name="gender" value="other"  {{($user->Profile->gender == 'other') ? 'checked' : ''}}> {{trans('messages.other')}}
															</div>
														</div>
													</div>
													<div class="col-xs-6">
													  	<div class="form-group">
															{!! Form::label('unique_identification_number',trans('messages.unique_identification_number'),[])!!}
															{!! Form::input('text','unique_identification_number',$user->Profile->unique_identification_number,['class'=>'form-control','placeholder'=>trans('messages.unique_identification_number')])!!}
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-6">
													  	<div class="form-group">
															{!! Form::label('marital_status',trans('messages.marital').' '.trans('messages.status'),[])!!}
															{!! Form::select('marital_status', translateList('marital_status'),$user->Profile->marital_status,['class'=>'form-control show-tick','title'=>trans('messages.select_one')])!!}
														</div>
													</div>
													<div class="col-xs-6">
													  	<div class="form-group">
															{!! Form::label('nationality',trans('messages.nationality'),[])!!}
															{!! Form::input('text','nationality',$user->Profile->nationality,['class'=>'form-control','placeholder'=>trans('messages.nationality')])!!}
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group">
															{!! Form::label('phone',trans('messages.phone'))!!}
															{!! Form::input('text','phone',$user->Profile->phone,['class'=>'form-control','placeholder'=>trans('messages.phone')])!!}
															<div class="help-block">This will be used to send two factor auth code.</div>
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group">
															{!! Form::label('phone',trans('messages.home').' '.trans('messages.phone'))!!}
															{!! Form::input('text','home_phone',$user->Profile->home_phone,['class'=>'form-control','placeholder'=>trans('messages.home').' '.trans('messages.phone')])!!}
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-8">
													{!! Form::input('text','work_phone',$user->Profile->work_phone,['class'=>'form-control','placeholder'=>trans('messages.work')])!!}
													</div>
													<div class="col-xs-4">
													{!! Form::input('text','work_phone_extension',$user->Profile->work_phone_extension,['class'=>'form-control','placeholder'=>trans('messages.ext')])!!}
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													{!! Form::label('email',trans('messages.work').' '.trans('messages.email'))!!}
													{!! Form::input('email','email',$user->email,['class'=>'form-control','placeholder'=>trans('messages.work').' '.trans('messages.email')])!!}
												</div>
											  <div class="form-group">
												{!! Form::label('date_of_birth',trans('messages.date_of').' '.trans('messages.birth'),[])!!}
												{!! Form::input('text','date_of_birth',$user->Profile->date_of_birth,['class'=>'form-control datepicker','placeholder'=>trans('messages.date_of').' '.trans('messages.birth')])!!}
											  </div>
											  <div class="form-group">
												{!! Form::label('date_of_anniversary',trans('messages.date_of').' '.trans('messages.anniversary'),[])!!}
												{!! Form::input('text','date_of_anniversary',$user->Profile->date_of_anniversary,['class'=>'form-control datepicker','placeholder'=>trans('messages.date_of').' '.trans('messages.anniversary')])!!}
											  </div>
												<div class="form-group">
													{!! Form::label('address',trans('messages.address'),[])!!}
													{!! Form::input('text','address_line_1',$user->Profile->address_line_1,['class'=>'form-control','placeholder'=>trans('messages.address_line_1')])!!}
													<br />
													{!! Form::input('text','address_line_2',$user->Profile->address_line_2,['class'=>'form-control','placeholder'=>trans('messages.address_line_2')])!!}
													<br />
													<div class="row">
														<div class="col-xs-5">
														{!! Form::input('text','city',$user->Profile->city,['class'=>'form-control','placeholder'=>trans('messages.city')])!!}
														</div>
														<div class="col-xs-4">
														{!! Form::input('text','state',$user->Profile->state,['class'=>'form-control','placeholder'=>trans('messages.state')])!!}
														</div>
														<div class="col-xs-3">
														{!! Form::input('text','zipcode',$user->Profile->zipcode,['class'=>'form-control','placeholder'=>trans('messages.postcode')])!!}
														</div>
													</div>
													<br />
													{!! Form::select('country_id', [null => trans('messages.select_one')] + config('country'),$user->Profile->country_id,['class'=>'form-control show-tick','title'=>trans('messages.country')])!!}
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												{{ getCustomFields('user-registration-form',$custom_register_field_values) }}
											</div>
										</div>
										{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
									{!! Form::close() !!}
								@else
									@include('user.basic')
								@endif
							</div>
						  </div>
						  @if(!$user->hasRole(DEFAULT_ROLE))
						  <div class="tab-pane animated fadeInRight" id="employment-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
								<h2><strong>{{ trans('messages.employment')}}</strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-employment.store',$user->id] ,'class' => 'user-employment-form','id' => 'user-employment-form','data-table-refresh' => 'user-employment-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_employment._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.employment') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-employment-table" data-source="/user-employment/lists" data-extra="&id={{$user->id}}">
										<thead>
											<tr>
												<th>{!! trans('messages.date_of').' '.trans('messages.joining') !!}</th>
												<th>{!! trans('messages.date_of').' '.trans('messages.leaving') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  @endif
						  @if(Entrust::can('edit-user'))
						  <div class="tab-pane animated fadeInRight" id="avatar-tab">
							<div class="user-profile-content-wm">
								<h2><strong>{{ trans('messages.avatar') }} </strong></h2>
								{!! Form::model($user,['files' => true, 'method' => 'POST','route' => ['user.avatar',$user->id] ,'class' => 'user-avatar-form','id' => 'user-avatar-form']) !!}
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<input type="file" name="avatar" id="avatar" title="{!! trans('messages.select').' '.trans('messages.avatar') !!}" class="btn btn-default file-input" data-buttonText="{!! trans('messages.select').' '.trans('messages.avatar') !!}">
											</div>
										</div>
									</div>
									@if($user->Profile->avatar && File::exists(config('constant.upload_path.avatar').$user->Profile->avatar))
									<div class="form-group">
										<img src="{!! URL::to(config('constant.upload_path.avatar').$user->Profile->avatar) !!}" width="150px" style="margin-left:20px;">
										<div class="checkbox">
											<label>
											  <input name="remove_avatar" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" data-off-value="0"> {!! trans('messages.remove').' '.trans('messages.avatar') !!}
											</label>
										</div>
									</div>
									@endif
									{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary']) !!}
								{!! Form::close() !!}
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="social-tab">
							<div class="user-profile-content-wm">
								<h2><strong>{{ trans('messages.social') }} </strong></h2>
								{!! Form::model($user,['method' => 'POST','route' => ['user.social-update',$user->id] ,'class' => 'user-social-form','id' => 'user-social-form','data-no-form-clear' => 1,'data-refresh' => 'load-user-detail']) !!}
								  <div class="form-group">
									{!! Form::label('facebook','Facebook',[])!!}
									{!! Form::input('text','facebook',$user->Profile->facebook,['class'=>'form-control','placeholder'=>'Facebook'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::label('twitter','Twitter',[])!!}
									{!! Form::input('text','twitter',$user->Profile->twitter,['class'=>'form-control','placeholder'=>'Twitter'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::label('google_plus','Google Plus',[])!!}
									{!! Form::input('text','google_plus',$user->Profile->google_plus,['class'=>'form-control','placeholder'=>'Google Plus'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::label('github','Github',[])!!}
									{!! Form::input('text','github',$user->Profile->github,['class'=>'form-control','placeholder'=>'Github'])!!}
								  </div>
								{{ getCustomFields('user-social-form',$custom_social_field_values) }}
								{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
								{!! Form::close() !!}
							</div>
						  </div>
						  @endif
						  <div class="tab-pane animated fadeInRight" id="contact-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.contact') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-contact.store',$user->id] ,'class' => 'user-contact-form','id' => 'user-contact-form','data-table-refresh' => 'user-contact-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_contact._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.contact') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-contact-table" data-source="/user-contact/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.name') !!}</th>
												<th>{!! trans('messages.relation') !!}</th>
												<th>{!! trans('messages.email') !!}</th>
												<th>{!! trans('messages.mobile') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="bank-account-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.bank') }} </strong> {{trans('messages.account')}}</h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-bank-account.store',$user->id] ,'class' => 'user-bank-account-form','id' => 'user-bank-account-form','data-table-refresh' => 'user-bank-account-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_bank_account._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.account') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-bank-account-table" data-source="/user-bank-account/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.name') !!}</th>
												<th>{!! trans('messages.number') !!}</th>
												<th>{!! trans('messages.bank').' '.trans('messages.name') !!}</th>
												<th>{!! trans('messages.branch') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="document-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.document') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-document.store',$user->id] ,'class' => 'user-document-form','id' => 'user-document-form','data-table-refresh' => 'user-document-table','data-file-upload' => '.file-uploader','data-refresh' => 'load-user-detail']) !!}
									  @include('user_document._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.document') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-document-table" data-source="/user-document/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.type') !!}</th>
												<th>{!! trans('messages.title') !!}</th>
												<th>{!! trans('messages.date_of').' '.trans('messages.expiry') !!}</th>
												<th>{!! trans('messages.date') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  @if(!$user->hasRole(DEFAULT_ROLE))
						  <div class="tab-pane animated fadeInRight" id="designation-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.designation') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-designation.store',$user->id] ,'class' => 'user-designation-form','id' => 'user-designation-form','data-table-refresh' => 'user-designation-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_designation._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.designation') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-designation-table" data-source="/user-designation/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.designation') !!}</th>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  @endif
						  <div class="tab-pane animated fadeInRight" id="location-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.location') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-location.store',$user->id] ,'class' => 'user-location-form','id' => 'user-location-form','data-table-refresh' => 'user-location-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_location._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.location') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-location-table" data-source="/user-location/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.location') !!}</th>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="contract-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.contract') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-contract.store',$user->id] ,'class' => 'user-contract-form','id' => 'user-contract-form','data-table-refresh' => 'user-contract-table','data-file-upload' => '.file-uploader','data-refresh' => 'load-user-detail']) !!}
									  @include('user_contract._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.contract') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-contract-table" data-source="/user-contract/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.type') !!}</th>
												<th>{!! trans('messages.title') !!}</th>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="shift-tab">
							<div class="user-profile-content-wm">
								<h2>{!! trans('messages.list_all').' '.trans('messages.shift') !!}</h2>
								@if(Entrust::can('edit-user'))
								<div class="additional-btn">
									<a href="#" data-href="/user-shift/{{$user->id}}/create" class="btn btn-primary btn-xs" data-target="#myModal" data-toggle="modal">{{trans('messages.add_new')}}</a>
								</div>
								@endif

								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-shift-table" data-source="/user-shift/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.name') !!}</th>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="leave-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.leave') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-leave.store',$user->id] ,'class' => 'user-leave-form','id' => 'user-leave-form','data-table-refresh' => 'user-leave-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_leave._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.leave') !!}</h2>

								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-leave-table" data-source="/user-leave/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												@foreach($leave_types as $leave_type)
													<th>{!! $leave_type->name !!}</th>
												@endforeach
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="salary-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.salary') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-salary.store',$user->id] ,'class' => 'user-salary-form','id' => 'user-salary-form','data-table-refresh' => 'user-salary-table','data-refresh' => 'load-user-detail']) !!}
									  @include('user_salary._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.salary') !!}</h2>

								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-salary-table" data-source="/user-salary/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.from').' '.trans('messages.date') !!}</th>
												<th>{!! trans('messages.to').' '.trans('messages.date') !!}</th>
												<th>{{trans('messages.type')}}</th>
												<th>{{trans('messages.hourly_rate')}}</th>
												@foreach($earning_salary_heads as $earning_salary_head)
													<th>{!! $earning_salary_head->name !!}</th>
												@endforeach
												@foreach($deduction_salary_heads as $deduction_salary_head)
													<th>{!! $deduction_salary_head->name !!}</th>
												@endforeach
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="qualification-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.qualification') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-qualification.store',$user->id] ,'class' => 'user-qualification-form','id' => 'user-qualification-form','data-table-refresh' => 'user-qualification-table','data-file-upload' => '.file-uploader','data-refresh' => 'load-user-detail']) !!}
									  @include('user_qualification._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.qualification') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-qualification-table" data-source="/user-qualification/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.institute').' '.trans('messages.name') !!}</th>
												<th>{!! trans('messages.duration') !!}</th>
												<th>{!! trans('messages.education').' '.trans('messages.level') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="experience-tab">
							<div class="user-profile-content-wm">
								@if(Entrust::can('edit-user'))
									<h2><strong>{{ trans('messages.experience') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user-experience.store',$user->id] ,'class' => 'user-experience-form','id' => 'user-experience-form','data-table-refresh' => 'user-experience-table','data-file-upload' => '.file-uploader','data-refresh' => 'load-user-detail']) !!}
									  @include('user_experience._form')
									{!! Form::close() !!}
								@endif

								<h2>{!! trans('messages.list_all').' '.trans('messages.experience') !!}</h2>
								<div class="table-responsive">
									<table data-sortable class="table table-hover table-striped table-bordered ajax-table"  id="user-experience-table" data-source="/user-experience/lists" data-extra="&id={{$user->id}}&show_option=1">
										<thead>
											<tr>
												<th>{!! trans('messages.company').' '.trans('messages.name') !!}</th>
												<th>{!! trans('messages.duration') !!}</th>
												<th>{!! trans('messages.job').' '.trans('messages.title') !!}</th>
												<th data-sortable="false">{!! trans('messages.option') !!}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						  </div>
						  @if($user->id != Auth::user()->id && Entrust::can('reset-user-password'))
						  <div class="tab-pane animated fadeInRight" id="reset-password-tab">
							<div class="user-profile-content-wm">
								<h2><strong>{{ trans('messages.reset').' '.trans('messages.password') }} </strong></h2>
								{!! Form::model($user,['method' => 'POST','route' => ['user.force-change-password',$user->id] ,'class' => 'user-force-change-password-form','id' => 'user-force-change-password-form']) !!}
									<div class="form-group">
										{!! Form::label('new_password',trans('messages.new').' '.trans('messages.password'),[])!!}
										{!! Form::input('password','new_password','',['class'=>'form-control '.(config('config.enable_password_strength_meter') ? 'password-strength' : ''),'placeholder'=>trans('messages.new').' '.trans('messages.password')])!!}
									</div>
									<div class="form-group">
										{!! Form::label('new_password_confirmation',trans('messages.confirm').' '.trans('messages.password'),[])!!}
										{!! Form::input('password','new_password_confirmation','',['class'=>'form-control','placeholder'=>trans('messages.confirm').' '.trans('messages.password')])!!}
									</div>
									<div class="form-group">
										{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.update'),['class' => 'btn btn-primary pull-right']) !!}
									</div>
								{!! Form::close() !!}
							</div>
						  </div>
						  @endif
						  @if(config('config.enable_email_template') && Entrust::can('email-user'))
							<div class="tab-pane animated fadeInRight" id="email-tab">
								<div class="user-profile-content-wm">
									<h2><strong>{{ trans('messages.email').' '.trans('messages.user') }} </strong></h2>
									{!! Form::model($user,['method' => 'POST','route' => ['user.email',$user->id] ,'class' => 'user-email-form','id' => 'user-email-form','data-user-id' => $user->id,'data-url' => '/template/content']) !!}
									<div class="form-group">
										{!! Form::select('template_id', $templates,'',['class'=>'form-control show-tick','id'=>'template_id','title' => trans('messages.select_one')])!!}
									</div>
									<div class="form-group">
										{!! Form::input('text','subject','',['class'=>'form-control','placeholder'=>trans('messages.subject'),'id' => 'mail_subject']) !!}
									</div>
									<div class="form-group">
										{!! Form::textarea('body','',['size' => '30x3', 'class' => 'form-control summernote', 'id' => 'mail_body', 'placeholder' => trans('messages.body')])!!}
									</div>
									{!! Form::submit(trans('messages.send'),['class' => 'btn btn-primary pull-right']) !!}
									{!! Form::close() !!}
								</div>
							</div>
						  @endif
						</div>
					</div>
				</div>
			</div>
		</div>
	@stop
