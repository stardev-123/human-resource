
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h4 class="modal-title">{!! $user->full_name.' '.trans('messages.profile') !!}</h4>
	</div>
	<div class="modal-body">
	    <div class="progress">
	        <div class="progress-bar progress-bar-{{progressColor($setup_percentage)}}" role="progressbar" aria-valuenow="{{$setup_percentage}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$setup_percentage}}%;">
	        {{$setup_percentage}}%
	        </div>
	    </div>
	    @if($setup_percentage < 100)
	    	<?php $i = 1; ?>
		    @foreach($setup as $key => $value)
		    	@if($i % 3 == 0 || $i == 1)
		    		<div class="row" style="padding:5px;">
		    	@endif
	            <div class="col-xs-4">
	                @if($value['status'])
	                    <i class="fa fa-check-circle success fa-2x" style="vertical-align:middle;"></i> {{toWordTranslate($key)}}
	                @else
	                    <i class="fa fa-times-circle danger fa-2x" style="vertical-align:middle;"></i> {{toWordTranslate($key)}}
	                @endif
	            </div>
	            @if($i % 3 == 0)
		    		</div>
		    	@endif
		    	<?php $i++; ?>
		    @endforeach
		@else
	        <p class="alert alert-success">Your profile is completed!</p>
	    @endif
	</div>