			
			  <div class="form-group">
			    {!! Form::label('max_file_size_upload',trans('messages.max_file_size_upload'),[])!!}
			    <div class="input-group">
					{!! Form::input('number','max_file_size_upload',(config('config.max_file_size_upload')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.max_file_size_upload')])!!}
					<span class="input-group-addon">MB</span>
				</div>
				<span class="help-block">{{trans('messages.system_max_file_size_upload',['attribute' => formatMemorySizeUnits(getMaxFileUploadSize())])}}</span>
			  </div>

		  		<div class="row">
		  			<div class="col-md-4">
		  			</div>
		  			<div class="col-md-4">
						{!! Form::label('',trans('messages.limit'),[])!!}
		  			</div>
		  			<div class="col-md-4">
						{!! Form::label('',trans('messages.allowed_upload_file_type'),[])!!}
		  			</div>
		  		</div>
		  		<div class="custom-scrollbar" style="max-height: 800px;">
				  @foreach(config('upload') as $key => $value)
				  		<div class="row">
				  			<div class="col-md-4">
				  				<strong>{{toWordTranslate($key)}}</strong>
				  			</div>
				  			<div class="col-md-2">
				  				<div class="form-group">
									{!! Form::input('number','limit['.$key.']',(config('upload.'.$key.'.limit')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.limit')])!!}
								</div>
				  			</div>
				  			<div class="col-md-6">
								<div class="form-group">
									{!! Form::input('text','extension['.$key.']',(config('upload.'.$key.'.extension')) ? : '',['class'=>'form-control','placeholder'=>trans('messages.allowed_upload_file_type'),'data-role' => 'tagsinput'])!!}
								</div>
				  			</div>
				  		</div>
				  @endforeach
				</div>
			<input type="hidden" name="config_type" class="hidden_fields" value="upload">
		  	{!! Form::submit(trans('messages.save'),['class' => 'btn btn-primary pull-right','style' => 'margin-top:15px;']) !!}
			<div class="clear"></div>