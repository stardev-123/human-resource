
			  <div class="form-group">
			    {!! Form::label('department_id',trans('messages.department'),[])!!}
				{!! Form::select('department_id', $departments,isset($designation) ? $designation->department_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one')])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('top_designation_id',trans('messages.top').' '.trans('messages.designation'),[])!!}
				{!! Form::select('top_designation_id', $top_designations,(isset($designation)) ? $designation->top_designation_id : '',['class'=>'form-control input-xlarge show-tick','title' => trans('messages.select_one'),'id' => 'top_designation_id'])!!}
			  </div>
			  <div class="form-group">
			    {!! Form::label('name',trans('messages.designation'),[])!!}
				{!! Form::input('text','name',isset($designation) ? $designation->name : '',['class'=>'form-control','placeholder'=>trans('messages.designation')])!!}
			  </div>
			  @if(isset($designation) && $designation->is_default)
			  	<div class="form-group">
			  		<span class="label label-danger">{{trans('messages.user').' '.trans('messages.default')}}</span>
			  	</div>
			  @else
			  <div class="form-group">
                <input name="is_default" type="checkbox" class="switch-input" data-size="mini" data-on-text="Yes" data-off-text="No" value="1"> {{trans('messages.user').' '.trans('messages.default')}}
              </div>
			  @endif
			  	{{ getCustomFields('designation-form',$custom_field_values) }}
			  	{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
