@foreach($activities as $activity)
	<div>{!! getAvatar($activity->user_id,45) !!} <strong>{{$activity->User->full_name}}</strong> {{trans('messages.'.$activity->activity)}} on <strong>{{showDateTime($activity->created_at)}}</strong> </div>
	<div class="clear" style="margin-bottom: 5px;"></div>
@endforeach