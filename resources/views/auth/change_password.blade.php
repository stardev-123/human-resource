
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.change').' '.trans('messages.password') !!}</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				{!! Form::open(['route' => 'change-password','role' => 'form', 'class' => 'change-password-form','id' => "change-password-form"]) !!}

				<div class="form-group">
				    {!! Form::label('old_password',trans('messages.current').' '.trans('messages.password'),[])!!}
					{!! Form::input('password','old_password','',['class'=>'form-control','placeholder'=>trans('messages.current').' '.trans('messages.password')])!!}
				</div>
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
	</div>