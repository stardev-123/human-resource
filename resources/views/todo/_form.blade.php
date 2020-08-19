
			<div class="row">
				<div class="col-md-12">
				  <div class="form-group">
				    {!! Form::label('date',trans('messages.date'),[])!!}
					{!! Form::input('text','date',isset($todo->date) ? $todo->date : '',['class'=>'form-control datepicker','placeholder'=>trans('messages.date'),'readonly' => 'true'])!!}
				  </div>
				  <div class="form-group">
				    {!! Form::label('title',trans('messages.title'),[])!!}
					{!! Form::input('text','title',isset($todo->title) ? $todo->title : '',['class'=>'form-control','placeholder'=>trans('messages.title')])!!}
				  </div>
					<div class="form-group">
					    {!! Form::label('description',trans('messages.description'),[])!!}
					    {!! Form::textarea('description',isset($todo->description) ? $todo->description : '',['size' => '30x3', 'class' => 'form-control', 'placeholder' => trans('messages.description')])!!}
					    <span class="countdown"></span>
					</div>
					<div class="form-group">
					  {!! Form::label('visibility',trans('messages.visibility'),['class' => 'col-sm-2'])!!}
						<div class="col-sm-10">
							<label class="checkbox-inline">
								<input type="radio" name="visibility" id="visibility" value="private" class="icheck" {{ (isset($todo->visibility) && $todo->visibility != 'public') ? 'checked' : '' }}  > {!! trans('messages.private') !!}
							</label>
							<label class="checkbox-inline">
								<input type="radio" name="visibility" id="visibility" class="icheck" value="public" {{ (isset($todo->visibility) && $todo->visibility == 'public') ? 'checked' : '' }}> {!! trans('messages.public') !!}
							</label>
						</div>
					</div>
					<div class="form-group">
				  		{!! Form::submit(isset($buttonText) ? $buttonText : trans('messages.save'),['class' => 'btn btn-primary pull-right']) !!}
				  	</div>
				</div>
			</div>
			  	
