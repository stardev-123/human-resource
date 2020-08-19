
			  <div class="form-group">
			    {!! Form::label('user_id',trans('messages.user'),[])!!}
				{!! Form::select('user_id', [Auth::user()->id => Auth::user()->name_with_designation_and_department],Auth::user()->id,['class'=>'form-control show-tick','title' => trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('date',trans('messages.date'),[])!!}
				{!! Form::input('text','date',isset($daily_report) ? $daily_report->date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.date')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('description',trans('messages.description'),[])!!}
			    {!! Form::textarea('description',isset($daily_report) ? $daily_report->description : '',['size' => '30x3', 'class' => 'form-control summernote', 'placeholder' => trans('messages.description')])!!}
			  </div>
			  @include('upload.index',['module' => 'daily-report','upload_button' => trans('messages.upload').' '.trans('messages.file'),'module_id' => isset($daily_report) ? $daily_report->id : ''])
				{{ getCustomFields('daily-report-form',$custom_field_values) }}
				{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
