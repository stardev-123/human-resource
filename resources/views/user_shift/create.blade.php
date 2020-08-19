	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! trans('messages.add_new').' '.trans('messages.shift') !!}</h4>
	</div>
	<div class="modal-body">
		{!! Form::model($user,['method' => 'POST','route' => ['user-shift.store',$user->id] ,'class' => 'user-shift-form','id' => 'user-shift-form','data-table-refresh' => 'user-shift-table']) !!}
          @include('user_shift._form')
        {!! Form::close() !!}
	</div>
