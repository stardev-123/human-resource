@extends('layouts.guest')

	@section('content')

		<a class="btn btn-primary btn-sm pull-right" style='margin:15px;' role="button" href="http://mygemsystem.com" target=_blank>Documentation</a>
		
		<div class="full-content-center-more animated fadeInDownBig">
			<div class="login-wrap">
				<div class="box-info full">

					{!! Form::open(['route' => 'install.store','class' => 'install-form','id' => 'myWizard'])!!}
					<section class="step" data-step-title="Installation Guide">
							<div class="row">
								<div class="col-sm-12">
									@foreach($checks as $check)
										@if($check['type'] == 'error')
											<div class="alert alert-danger" style="padding:5px;margin:5px;"><i class="fa fa-times icon"></i> {{ $check['message'] }}</div>
										@else
											<div class="alert alert-success" style="padding:5px;margin:5px;"><i class="fa fa-check icon"></i> {{ $check['message'] }}</div>
										@endif
									@endforeach
								</div>
							</div>
					</section>
					<section class="step" data-step-title="Let's Get Ready">
						<div class="row">
							<div class="col-sm-12">
								@if($error)
								<div class="alert alert-danger">Please fix the error.</div>
								@else
								<p>You are ready to install. Get ready the following:</p>
								<ol>
									<li>Create a MYSQL database & get ready with its username & Password</li>
									<li>Envato Username & Purchase License Code</li>
								</ol>
								@endif
							</div>
						</div>
					</section>
					<section class="step" data-step-title="Let's Install">
						<div class="row">
							@if($error)
								<div class="col-sm-12">
									<div class="alert alert-danger">Please fix the error.</div>
								</div>
							@else
								<div class="col-sm-6">
								  <div class="form-group">
									{!! Form::input('text','hostname','',['class'=>'form-control','placeholder'=>'Enter Hostname'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('text','mysql_username','',['class'=>'form-control','placeholder'=>'Enter MYSQL Username'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('password','mysql_password','',['class'=>'form-control','placeholder'=>'Enter MYSQL Password'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('text','mysql_database','',['class'=>'form-control','placeholder'=>'Enter MYSQL Database'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('email','email','',['class'=>'form-control','placeholder'=>'Enter Email'])!!}
								  </div>
								</div>
								<div class="col-sm-6">
								  <div class="row">
									  <div class="col-md-6">
										  <div class="form-group">
											{!! Form::input('text','first_name','',['class'=>'form-control','placeholder'=>'Enter First Name'])!!}
										  </div>
									  </div>
									  <div class="col-md-6">
										  <div class="form-group">
											{!! Form::input('text','last_name','',['class'=>'form-control','placeholder'=>'Enter Last Name'])!!}
										  </div>
									  </div>
								  </div>
								  <div class="form-group">
									{!! Form::input('text','username','',['class'=>'form-control','placeholder'=>'Enter Username'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('password','password','',['class'=>'form-control '.(config('config.enable_password_strength_meter') ? 'password-strength' : ''),'placeholder'=>'Enter Password'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('text','envato_username','',['class'=>'form-control','placeholder'=>'Enter Envato Username'])!!}
								  </div>
								  <div class="form-group">
									{!! Form::input('text','purchase_code','',['class'=>'form-control','placeholder'=>'Enter Purchase Code'])!!}
								  </div>
								  {!! Form::submit('Install',['class' => 'btn btn-primary pull-right']) !!}
								</div>
							@endif
						</div>
					</section>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	@stop
