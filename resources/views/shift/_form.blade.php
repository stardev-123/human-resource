			<div class="row">
				<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('name',trans('messages.shift').' '.trans('messages.name'),[])!!}
					{!! Form::input('text','name',isset($shift->name) ? $shift->name : '',['class'=>'form-control','placeholder'=>trans('messages.shift').' '.trans('messages.name')])!!}
				  </div>
				  @if(isset($shift) && !$shift->is_default)
				  <div class="form-group">
	                <input name="is_default" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1" {{ (isset($shift) && $shift->is_default) ? 'checked' : '' }}> {!! trans('messages.default') !!}
	              </div>
	              @endif
				</div>
				<div class="col-md-6">
				  <div class="form-group">
				    {!! Form::label('description',trans('messages.description'),[])!!}
				    {!! Form::textarea('description',isset($shift) ? $shift->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description'),"data-show-counter" => 1,"data-limit" => config('config.textarea_limit'),'data-autoresize' => 1])!!}
				    <span class="countdown"></span>
				  </div>
				</div>
			</div>
			  @foreach(config('lists.week') as $day_name)
			  <div class="form-group row">
			  	  {!! Form::label('time',trans('messages.'.$day_name),['class' => 'col-md-2'])!!}
			  	  {!! Form::checkbox("overnight[$day_name]", 1, (isset($week) && strtotime($week['in_time'][$day_name]) > strtotime($week['out_time'][$day_name])) ? 'checked' : '',['class' => 'icheck']) !!} {!! trans('messages.overnight') !!} 
			  	  <div class="col-md-4">
				  {!! Form::input('text',"week[in_time][$day_name]",(isset($week) && $week['in_time'][$day_name] != $week['out_time'][$day_name]) ? $week['in_time'][$day_name]  : '',['class'=>'form-control timepicker','placeholder'=>trans('messages.in_time'),'readonly' => true])!!}
				  </div>
				  <div class="col-md-4">
				  {!! Form::input('text',"week[out_time][$day_name]",(isset($week) && $week['in_time'][$day_name] != $week['out_time'][$day_name]) ? $week['out_time'][$day_name]  : '',['class'=>'form-control timepicker','placeholder'=>trans('messages.out_time'),'readonly' => true])!!}
				  </div>
			  </div>
			  @endforeach
			  {{ getCustomFields('shift-form',$custom_field_values) }}
			  {!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}

			  @if(isset($shift))
			  	<div style="height: 250px;"> </div>
			  @endif

