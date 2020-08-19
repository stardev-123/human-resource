@extends('layouts.app')

	@section('breadcrumb')
		<ul class="breadcrumb">
		    <li><a href="/home">{!! trans('messages.home') !!}</a></li>
		    <li class="active">{!! trans('messages.configuration') !!}</li>
		</ul>
	@stop
	
	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info full">
					<div class="tabs-left">	
						<ul class="nav nav-tabs col-md-2 tab-list" style="padding-right:0;">
		                    <li><a href="#general-tab" data-toggle="tab">{{trans('messages.general')}}</a>
		                    </li>
		                    <li><a href="#logo-tab" data-toggle="tab">{{trans('messages.logo')}}</a>
		                    </li>
		                    <li><a href="#theme-tab" data-toggle="tab">{{trans('messages.theme')}}</a>
		                    </li>
		                    <li><a href="#system-tab" data-toggle="tab">{{trans('messages.system')}}</a>
		                    </li>
		                    <li><a href="#upload-tab" data-toggle="tab">{{trans('messages.upload')}}</a>
		                    </li>
		                    <li><a href="#mail-tab" data-toggle="tab">{{trans('messages.mail')}}</a>
		                    </li>
		                    <li><a href="#sms-tab" data-toggle="tab">SMS</a>
		                    </li>
		                    <li><a href="#auth-tab" data-toggle="tab">{{trans('messages.authentication')}}</a>
		                    </li>
		                    <li><a href="#social-login-tab" data-toggle="tab">{{trans('messages.social').' '.trans('messages.login')}}</a>
		                    </li>
		                    <li><a href="#menu-tab" data-toggle="tab">{{trans('messages.menu')}}</a>
		                    </li>
		                    <li><a href="#currency-tab" data-toggle="tab">{{trans('messages.currency')}}</a>
		                    </li>
		                    <li><a href="#user-tab" data-toggle="tab">{{trans('messages.user')}}</a>
		                    </li>
		                    <li><a href="#document-tab" data-toggle="tab">{{trans('messages.document')}}</a>
		                    </li>
		                    <li><a href="#award-tab" data-toggle="tab">{{trans('messages.award')}}</a>
		                    </li>
		                    <li><a href="#contract-tab" data-toggle="tab">{{trans('messages.contract')}}</a>
		                    </li>
		                    <li><a href="#expense-tab" data-toggle="tab">{{trans('messages.expense')}}</a>
		                    </li>
		                    <li><a href="#leave-tab" data-toggle="tab">{{trans('messages.leave')}}</a>
		                    </li>
		                    <li><a href="#attendance-tab" data-toggle="tab">{{trans('messages.attendance')}}</a>
		                    </li>
		                    <li><a href="#salary-tab" data-toggle="tab">{{trans('messages.salary')}}</a>
		                    </li>
		                    <li><a href="#task-tab" data-toggle="tab">{{trans('messages.task')}}</a>
		                    </li>
		                    <li><a href="#ticket-tab" data-toggle="tab">{{trans('messages.ticket')}}</a>
		                    </li>
		                    <li><a href="#qualification-tab" data-toggle="tab">{{trans('messages.qualification')}}</a></li>
		                    <li><a href="#notification-tab" data-toggle="tab">{{trans('messages.notification')}}</a></li>
		                    <li><a href="#api-tab" data-toggle="tab">API</a></li>
		                    <li><a href="#schedule-job-tab" data-toggle="tab">Schedule Job</a>
		                    </li>
		                </ul>

				        <div class="tab-content col-md-10 col-xs-10" style="padding:0px 25px 10px 25px;">
						  <div class="tab-pane animated fadeInRight" id="general-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.general') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-general-form','id' => 'config-general-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._general_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="logo-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.logo') }}</strong></h2>
						    	{!! Form::open(['files' => true, 'route' => 'configuration.logo','role' => 'form', 'class'=>'config-logo-form','id' => 'config-logo-form','data-no-form-clear' => 1]) !!}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="file" class="btn btn-default file-input" name="company_logo" id="company_logo" data-buttonText="{!! trans('messages.select').' '.trans('messages.logo') !!}">
                                            </div>
                                        </div>
                                    </div>
                                    @if(config('config.company_logo') && File::exists(config('constant.upload_path.company_logo').config('config.company_logo')))
                                    <div class="form-group">
                                        <img src="{{ URL::to(config('constant.upload_path.company_logo').config('config.company_logo')) }}" width="150px" style="margin-left:20px;">
                                        <div class="checkbox">
                                            <label>
                                              <input name="remove_logo" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" data-off-value="0"> {!! trans('messages.remove').' '.trans('messages.logo') !!}
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                {!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="system-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.system') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-system-form','id' => 'config-system-form','data-disable-enter-submission' => '1','data-no-form-clear' => 1]) !!}
                                    @include('configuration._system_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="notification-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.notification') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-notification-form','id' => 'config-notification-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._notification_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="upload-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.upload') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.upload','role' => 'form', 'class'=>'config-upload-form','id' => 'config-upload-form','data-disable-enter-submission' => '1','data-no-form-clear' => 1]) !!}
                                    @include('configuration._upload_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="theme-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>Theme</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-theme-form','id' => 'config-theme-form','data-no-form-clear' => 1,'data-redirect' => '/configuration']) !!}
                                    @include('configuration._theme_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="mail-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{trans('messages.mail')}}</strong> {{ trans('messages.mail') }}</h2>
						    	{!! Form::open(['route' => 'configuration.mail','role' => 'form', 'class'=>'config-mail-form','id' => 'config-mail-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._mail_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="sms-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>SMS</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.sms','role' => 'form', 'class'=>'config-sms-form','id' => 'config-sms-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._sms_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="auth-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.authentication') }}</strong></h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-auth-form','id' => 'config-auth-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._auth_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="social-login-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.social') }}</strong> {{ trans('messages.login') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-social-login-form','id' => 'config-social-login-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._social_login_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="menu-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.menu') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.menu','role' => 'form', 'class'=>'config-menu-form','id' => 'config-menu-form','data-draggable' => 1,'data-no-form-clear' => 1,'data-sidebar' => 1]) !!}
								<div class="draggable-container">
									<?php $i = 0; ?>
									@foreach(\App\Menu::orderBy('order')->orderBy('id')->get() as $menu_item)
										<?php $i++; ?>
									  <div class="draggable" data-name="{{$menu_item->name}}" data-index="{{$i}}">
									    <p><input type="checkbox" class="icheck" name="{{$menu_item->name}}-visible" value="1" {{($menu_item->visible) ? 'checked' : ''}}> <span style="margin-left:50px;">{{trans('messages.'.$menu_item->name)}}</span></p>
									  </div>
									@endforeach
								</div>
								{!! Form::hidden('config_type','menu')!!}
			  					{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
								{!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="currency-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{!! trans('messages.currency').' '.trans('messages.configuration') !!}</strong></h2>
						    	<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.currency') !!} </h2>
											{!! Form::open(['route' => 'currency.store','class'=>'currency-form','id' => 'currency-form','data-table-refresh' => 'currency-table']) !!}
												@include('currency._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all').'</strong> '.trans('messages.currency') !!} </h2>
											<div class="table-responsive">
												<table data-sortable class="table table-hover table-striped ajax-table show-table" id="currency-table" data-source="/currency/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.name') !!}</th>
															<th>{!! trans('messages.symbol') !!}</th>
															<th>{!! trans('messages.position') !!}</th>
															<th>{!! trans('messages.default') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="user-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.user') }}</strong> {{ trans('messages.configuration') }}</h2>
						    	{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-user-form','id' => 'config-user-form','data-no-form-clear' => 1]) !!}
                                    @include('configuration._user_form')
                                {!! Form::close() !!}
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="award-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.award') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.award').' '.trans('messages.category') !!}</h2>
											{!! Form::open(['route' => 'award-category.store','role' => 'form', 'class'=>'award-category-form','id' => 'award-category-form','data-table-refresh' => 'award-category-table']) !!}
												@include('award_category._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.award').' '.trans('messages.category') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="award-category-table" data-source="/award-category/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.category') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="document-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.document') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.document').' '.trans('messages.type') !!}</h2>
											{!! Form::open(['route' => 'document-type.store','role' => 'form', 'class'=>'document-type-form','id' => 'document-type-form','data-table-refresh' => 'document-type-table']) !!}
												@include('document_type._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.document').' '.trans('messages.type') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="document-type-table" data-source="/document-type/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.type') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="contract-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.contract') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.contract').' '.trans('messages.type') !!}</h2>
											{!! Form::open(['route' => 'contract-type.store','role' => 'form', 'class'=>'contract-type-form','id' => 'contract-type-form','data-table-refresh' => 'contract-type-table']) !!}
												@include('contract_type._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.contract').' '.trans('messages.type') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="contract-type-table" data-source="/contract-type/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.type') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="leave-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.leave') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.leave').' '.trans('messages.type') !!}</h2>
											{!! Form::open(['route' => 'leave-type.store','role' => 'form', 'class'=>'leave-type-form','id' => 'leave-type-form','data-table-refresh' => 'leave-type-table']) !!}
												@include('leave_type._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.leave').' '.trans('messages.type') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="leave-type-table" data-source="/leave-type/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.type') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="box-info">
											<h2><strong>{!! trans('messages.leave') !!}</strong> {!! trans('messages.approval') !!} </h2>
											{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'configuration-leave-approval-form','id' => 'configuration-leave-approval-form','data-no-form-clear' => 1]) !!}
											  <div class="form-group">
												{!! Form::label('leave_approval_level',trans('messages.leave_approval_level'),['class' => ' control-label'])!!}
												{!! Form::select('leave_approval_level', [
													'single' => trans('messages.single').' '.trans('messages.level'),
													'multiple' => trans('messages.multiple').' '.trans('messages.level'),
													'last' => trans('messages.last').' '.trans('messages.level'),
													'designation' => trans('messages.designation'),
												],config('config.leave_approval_level'),['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
											  </div>
												<div class="form-group leave_no_of_level">
			    									{!! Form::label('leave_no_of_level',trans('messages.no_of').' '.trans('messages.level'),[])!!}
													<input type="number" name="leave_no_of_level" class="form-control" value="{{config('config.leave_no_of_level')}}" placeholder="{{trans('messages.no_of').' '.trans('messages.level')}}">
												</div>
												<div class="form-group leave_approval_level_designation">
			    									{!! Form::label('leave_approval_level_designation',trans('messages.designation'),[])!!}
			    									{!! Form::select('leave_approval_level_designation', childDesignation(),config('config.leave_approval_level_designation'),['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
			    								</div>
				  								{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
											{!! Form::close() !!}
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="attendance-tab">
						    <div class="user-profile-content-wm">
								<h2><strong>{!! trans('messages.attendance') !!}</strong> {!! trans('messages.configuration') !!} </h2>
								<div class="row">
									<div class="col-sm-12">
										<div class="box-info">
											<h2><strong>{!! trans('messages.attendance') !!}</strong> {!! trans('messages.configuration') !!} </h2>
											{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'config-attendance-form','id' => 'config-attendance-form','data-no-form-clear' => 1]) !!}
												<div class="form-group">
													{!! Form::label('enable_attendance_auto_clock',trans('messages.enable').' '.trans('messages.auto').' '.trans('messages.clock').' '.trans('messages.attendance'),['class' => 'control-label '])!!}
													<div class="checkbox">
														<input name="enable_attendance_auto_clock" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_attendance_auto_clock') == 1) ? 'checked' : '' }} data-off-value="0">
													</div>
												</div>
												<div class="form-group">
													{!! Form::label('enable_attendance_auto_lock',trans('messages.enable').' '.trans('messages.attendance').' '.trans('messages.auto').' '.trans('messages.lock'),['class' => 'control-label '])!!}
													<div class="checkbox">
														<input name="enable_attendance_auto_lock" type="checkbox" class="switch-input enable-show-hide" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.enable_attendance_auto_lock') == 1) ? 'checked' : '' }} data-off-value="0">
													</div>
												</div>
												<div id="enable_attendance_auto_lock_field">
													<div class="form-group">
														{!! Form::label('attendance_auto_lock_days',trans('messages.no_of').' '.trans('messages.day'),['class' => 'control-label '])!!}
														{!! Form::input('number','attendance_auto_lock_days',(config('config.attendance_auto_lock_days')) ? config('config.attendance_auto_lock_days') : '1',['class'=>'form-control'])!!}
													</div>
												</div>
												<div class="form-group">
													<input type="hidden" name="config_type" class="attendance" value="social_login">
													{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
												</div>
			                                {!! Form::close() !!}
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="expense-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.expense') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.expense').' '.trans('messages.head') !!}</h2>
											{!! Form::open(['route' => 'expense-head.store','role' => 'form', 'class'=>'expense-head-form','id' => 'expense-head-form','data-table-refresh' => 'expense-head-table']) !!}
												@include('expense_head._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.expense').' '.trans('messages.head') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="expense-head-table" data-source="/expense-head/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.head') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="box-info">
											<h2><strong>{!! trans('messages.expense') !!}</strong> {!! trans('messages.approval') !!} </h2>
											{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'configuraiton-expense-approval-form','id' => 'configuraiton-expense-approval-form','data-no-form-clear' => 1]) !!}
											  <div class="form-group">
												{!! Form::label('expense_approval_level',trans('messages.expense_approval_level'),['class' => ' control-label'])!!}
												{!! Form::select('expense_approval_level', [
													'single' => trans('messages.single').' '.trans('messages.level'),
													'multiple' => trans('messages.multiple').' '.trans('messages.level'),
													'last' => trans('messages.last').' '.trans('messages.level'),
													'designation' => trans('messages.designation'),
												],config('config.expense_approval_level'),['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
											  </div>
												<div class="form-group expense_no_of_level">
			    									{!! Form::label('expense_no_of_level',trans('messages.no_of').' '.trans('messages.level'),[])!!}
													<input type="number" name="expense_no_of_level" class="form-control" value="{{config('config.expense_no_of_level')}}" placeholder="{{trans('messages.no_of').' '.trans('messages.level')}}">
												</div>
												<div class="form-group expense_approval_level_designation">
			    									{!! Form::label('expense_approval_level_designation',trans('messages.designation'),[])!!}
			    									{!! Form::select('expense_approval_level_designation', childDesignation(),config('config.expense_approval_level_designation'),['class'=>'form-control show-tick','placeholder'=>trans('messages.select_one')])!!}
			    								</div>
				  								{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
											{!! Form::close() !!}
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="salary-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.salary') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.salary').' '.trans('messages.head') !!}</h2>
											{!! Form::open(['route' => 'salary-head.store','role' => 'form', 'class'=>'salary-head-form','id' => 'salary-head-form','data-table-refresh' => 'salary-head-table']) !!}
												@include('salary_head._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.salary').' '.trans('messages.head') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="salary-head-table" data-source="/salary-head/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.head') !!}</th>
															<th>{!! trans('messages.type') !!}</th>
															<th>{!! trans('messages.fixed') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="box-info">
											<h2><strong>{!! trans('messages.payroll') !!}</strong> {!! trans('messages.configuration') !!} </h2>
											{!! Form::open(['route' => 'configuration.store','role' => 'form', 'class'=>'configuration-payroll-form','id' => 'configuration-payroll-form','data-no-form-clear' => 1]) !!}
												<div class="row">
												  	<div class="col-md-6">
													  <div class="form-group">
													    {!! Form::label('payroll_include_day_summary',trans('messages.payroll_include_day_summary'),['class' => 'control-label '])!!}
										                <div class="checkbox">
										                <input name="payroll_include_day_summary" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.payroll_include_day_summary') == 1) ? 'checked' : '' }} data-off-value="0">
										                </div>
										              </div>
												  	</div>
												  	<div class="col-md-6">
													  <div class="form-group">
													    {!! Form::label('payroll_include_hour_summary',trans('messages.payroll_include_hour_summary'),['class' => 'control-label '])!!}
										                <div class="checkbox">
										                <input name="payroll_include_hour_summary" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.payroll_include_hour_summary') == 1) ? 'checked' : '' }} data-off-value="0">
										                </div>
										              </div>
												  	</div>
												  	<div class="col-md-6">
													  <div class="form-group">
													    {!! Form::label('payroll_include_leave_summary',trans('messages.payroll_include_leave_summary'),['class' => 'control-label '])!!}
										                <div class="checkbox">
										                <input name="payroll_include_leave_summary" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (config('config.payroll_include_leave_summary') == 1) ? 'checked' : '' }} data-off-value="0">
										                </div>
										              </div>
												  	</div>
												</div>
				  								{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
											{!! Form::close() !!}
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="task-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.task') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.task').' '.trans('messages.category') !!}</h2>
											{!! Form::open(['route' => 'task-category.store','role' => 'form', 'class'=>'task-category-form','id' => 'task-category-form','data-table-refresh' => 'task-category-table']) !!}
												@include('task_category._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.task').' '.trans('messages.category') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="task-category-table" data-source="/task-category/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.category') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.task').' '.trans('messages.priority') !!}</h2>
											{!! Form::open(['route' => 'task-priority.store','role' => 'form', 'class'=>'task-priority-form','id' => 'task-priority-form','data-table-refresh' => 'task-priority-table']) !!}
												@include('task_priority._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.task').' '.trans('messages.priority') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="task-priority-table" data-source="/task-priority/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.priority') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="ticket-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.ticket') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.ticket').' '.trans('messages.category') !!}</h2>
											{!! Form::open(['route' => 'ticket-category.store','role' => 'form', 'class'=>'ticket-category-form','id' => 'ticket-category-form','data-table-refresh' => 'ticket-category-table']) !!}
												@include('ticket_category._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.ticket').' '.trans('messages.category') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="ticket-category-table" data-source="/ticket-category/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.category') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.ticket').' '.trans('messages.priority') !!}</h2>
											{!! Form::open(['route' => 'ticket-priority.store','role' => 'form', 'class'=>'ticket-priority-form','id' => 'ticket-priority-form','data-table-refresh' => 'ticket-priority-table']) !!}
												@include('ticket_priority._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.ticket').' '.trans('messages.priority') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="ticket-priority-table" data-source="/ticket-priority/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.priority') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="qualification-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>{{ trans('messages.qualification') }}</strong> {{ trans('messages.configuration') }}</h2>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.education').' '.trans('messages.level') !!}</h2>
											{!! Form::open(['route' => 'education-level.store','role' => 'form', 'class'=>'education-level-form','id' => 'education-level-form','data-table-refresh' => 'education-level-table']) !!}
												@include('education_level._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.education').' '.trans('messages.level') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="education-level-table" data-source="/education-level/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.name') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.qualification').' '.trans('messages.language') !!}</h2>
											{!! Form::open(['route' => 'qualification-language.store','role' => 'form', 'class'=>'qualification-language-form','id' => 'qualification-language-form','data-table-refresh' => 'qualification-language-table']) !!}
												@include('qualification_language._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.qualification').' '.trans('messages.language') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="qualification-language-table" data-source="/qualification-language/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.name') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4">
										<div class="box-info">
											<h2><strong>{!! trans('messages.add_new') !!}</strong> {!! trans('messages.qualification').' '.trans('messages.skill') !!}</h2>
											{!! Form::open(['route' => 'qualification-skill.store','role' => 'form', 'class'=>'qualification-skill-form','id' => 'qualification-skill-form','data-table-refresh' => 'qualification-skill-table']) !!}
												@include('qualification_skill._form')
											{!! Form::close() !!}
										</div>
									</div>
									<div class="col-sm-8">
										<div class="box-info full">
											<h2><strong>{!! trans('messages.list_all') !!}</strong> {!! trans('messages.qualification').' '.trans('messages.skill') !!}</h2>
											<div class="table-responsive">
                        						<table data-sortable class="table table-hover table-striped ajax-table"  id="qualification-skill-table" data-source="/qualification-skill/lists">
													<thead>
														<tr>
															<th>{!! trans('messages.name') !!}</th>
															<th>{!! trans('messages.description') !!}</th>
															<th data-sortable="false">{!! trans('messages.option') !!}</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
						    </div>
						  </div>
						  <div class="tab-pane animated fadeInRight" id="api-tab">
						    <div class="user-profile-content-wm">
								<h2><strong>API</strong>{!! trans('messages.configuration') !!}</h2>
								<div class="row">
									<div class="col-sm-12">
										<div class="box-info">
											<h2><strong>{{trans('messages.generate')}}</strong> {{trans('messages.token')}} </h2>
											{!! Form::open(['route' => 'configuration.api','role' => 'form', 'class'=>'api-configuration-form','id'=>'api-configuration-form','data-no-form-clear' => 1]) !!}
												<div class="auth_token" id="auth_token" style="margin:20px 0px;">
												    {!! (Auth::user()->auth_token) ? Auth::user()->auth_token : '' !!}
												</div>
													{!! Form::hidden('config_type','api')!!}
													{!! Form::submit((Auth::user()->auth_token) ? trans('messages.regenerate').' '.trans('messages.token') : trans('messages.generate').' '.trans('messages.token'),['class' => 'btn btn-primary']) !!}
											{!! Form::close() !!}
										</div>
									</div>
								</div>
							</div>
						   </div>
						  <div class="tab-pane animated fadeInRight" id="schedule-job-tab">
						    <div class="user-profile-content-wm">
						    	<h2><strong>Schedule Job</strong></h2>
						    	<p>Add below cron command in your server:</p>
								<div class="well">
									php /path-to-artisan schedule:run >> /dev/null 2>&1
								</div>
								<div class="table-responsive">
									<table class="table table-stripped table-bordered table-hover">
										<thead>
											<tr>
												<th>Action</th>
												<th>Frequency</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Birthday/Anniversary wish to Staff</td>
												<td>Once per day at 09:00 AM</td>
											</tr>
											<tr>
												<td>Daily Backup</td>
												<td>Once per day at 01:00 AM</td>
											</tr>
											<tr>
												<td>Daily Summary Notification</td>
												<td>Once per day at 08:00 AM</td>
											</tr>
										</tbody>
									</table>
								</div>
						    </div>
						  </div>
						</div>
					</div>
				</div>
			</div>
		</div>

	@stop