@extends('layouts.app')

	@section('content')
		<div class="row">
			<div class="col-sm-12">
				<div class="box-info">
					<h2><strong>{!! trans('messages.edit') !!}</strong> {!! trans('messages.to_do') !!}
					<div class="additional-btn">
					{!! delete_form(['todo.destroy',$todo->id]) !!}
					</div>
					</h2>
					
					{!! Form::model($todo,['method' => 'PATCH','route' => ['todo.update',$todo->id] ,'class' => 'todo-form','id' => 'todo-form-edit','data-no-form-clear' => 1]) !!}
						@include('todo._form', ['buttonText' => trans('messages.update')])
					{!! Form::close() !!}
				</div>
			</div>
		</div>
		<div class="clear"></div>
	@stop
