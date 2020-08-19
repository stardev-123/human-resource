<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Notification;

Class NotificationController extends Controller{
    use BasicController;

    public function credential(Request $request){
        if(config('config.enable_push_notification') && \Auth::check())
            return response()->json(['notification' => 1,'key' => config('config.pusher_key'),'encrypted' => config('config.pusher_encrypted'),'cluster' => config('config.pusher_cluster')]);
        else
            return response()->json(['notification' => 0]);
    }

    public function index(){
        if(!config('config.enable_push_notification'))
            return redirect('/')->withErrors(trans('messages.invalid_link'));

		$data = array(
	        		trans('messages.option'),
	        		trans('messages.user'),
	        		trans('messages.description'),
	        		'',
	        		trans('messages.date')
        		);

		$table_data['notification-table'] = array(
				'source' => 'notification',
				'title' => trans('messages.notification').' '.trans('messages.list'),
				'id' => 'notification_table',
				'data' => $data,
                'form' => 'notification-filter-form'
			);

		$assets = ['datatable'];
		$menu = 'notification';
		return view('notification.index',compact('table_data','assets','menu'));
    }

	public function lists(Request $request){
        if(!config('config.enable_push_notification'))
            return;

        $query = \App\Notification::whereNotNull('id');

        if($request->input('status') == 'unread')
            $query->whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id]);
        elseif($request->input('status') == 'read')
            $query->whereRaw('NOT FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('FIND_IN_SET(?,user_read)', [\Auth::user()->id]);
        else
            $query->whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->orWhereRaw('FIND_IN_SET(?,user_read)', [\Auth::user()->id]);

        $notifications = $query->orderBy('id','desc')->get();

        $rows = array();
        foreach($notifications as $notification){
			$rows[] = array(
				'<div class="btn-group btn-group-xs">'.
				((in_array(\Auth::user()->id,explode(',',$notification->user))) ? '<a href="#" class="btn btn-xs btn-default" data-ajax="1" data-extra="&id='.$notification->id.'" data-source="/notification/mark-as-read" data-refresh="load-notification-message"> <i class="fa fa-eye" data-toggle="tooltip" title="'.trans('messages.mark').' '.trans('messages.as').' '.trans('messages.read').'"></i></a>' : '').
                '<a href="'.$notification->url.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.check').'" target="_blank"></i></a>'.
				'</div>',
				$notification->User->name_with_designation_and_department,
				$notification->description,
				timeAgo($notification->created_at),
				showDateTime($notification->created_at)
				);
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function markAsRead(Request $request){
        if(!config('config.enable_push_notification'))
            return;

		$notification = \App\Notification::whereId($request->input('id'))->whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id])->first();
        if($notification){
            updateReadNotification($notification);
        	return response()->json(['status' => 'success']);
        } else 
        	return response()->json(['status' => 'error','message' => trans('messages.invalid_link')]);
	}

    public function checkNotification(Request $request){
        if(!config('config.enable_push_notification'))
            return;

        $new_notification = ($request->has('id')) ? \App\Notification::whereId($request->input('id'))->whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id])->count() : 0;
        $notification_count = \App\Notification::whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id])->count();

        return response()->json(['new_notification' => $new_notification,'status' => 'success','notification_count' => $notification_count]);
    }

    public function loadNotification(Request $request){
        if(!config('config.enable_push_notification'))
            return;
        
        $user_id = \Auth::user()->id;
        $notifications = \App\Notification::whereRaw('FIND_IN_SET(?,user)', [$user_id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id])->orderBy('id','desc')->get();
        $top_notification = $notifications->take(5);

        return view('notification.message',compact('notifications','top_notification'))->render();
    }

}