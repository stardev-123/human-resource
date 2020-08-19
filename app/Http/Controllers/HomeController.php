<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class HomeController extends Controller
{
    use BasicController;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $announcements = \App\Announcement::where('from_date','<=',date('Y-m-d'))
                ->where('to_date','>=',date('Y-m-d'))->where(function($query){
            $query->whereIn('user_id',getAccessibleUserId(\Auth::user()->id,1))->orWhere(function($query1){
                $query1->where(function($query2){
                    $query2->where('audience','=','user')->whereHas('user',function($query3){
                        $query3->where('user_id','=',\Auth::user()->id);
                    });
                })->orWhere(function($query4){
                    $query4->where('audience','=','designation')->whereHas('designation',function($query5){
                        $query5->where('designation_id','=',\Auth::user()->Profile->designation_id);
                    });
                });
            });
        })->get();

        $all_birthdays = \App\Profile::whereBetween( \DB::raw('dayofyear(date_of_birth) - dayofyear(curdate())'), [0,config('config.celebration_days')])
            ->orWhereBetween( \DB::raw('dayofyear(curdate()) - dayofyear(date_of_birth)'), [0,config('config.celebration_days')])
            ->orderBy('date_of_birth','asc')
            ->get();

        $all_anniversaries = \App\Profile::whereBetween( \DB::raw('dayofyear(date_of_anniversary) - dayofyear(curdate())'), [0,config('config.celebration_days')])
            ->orWhereBetween( \DB::raw('dayofyear(curdate()) - dayofyear(date_of_anniversary)'), [0,config('config.celebration_days')])
            ->orderBy('date_of_anniversary','asc')
            ->get();

        $user_employment = array();
        foreach(\App\User::whereStatus('active')->get() as $active_user){
            $user_employment_detail = getEmployment(date('Y-m-d'),$active_user->id);
            if($user_employment_detail)
                $user_employment[] = $user_employment_detail->id;
        }

        $all_work_anniversaries = \App\UserEmployment::whereIn('id',$user_employment)->whereBetween( \DB::raw('dayofyear(date_of_joining) - dayofyear(curdate())'), [0,config('config.celebration_days')])
            ->orWhereBetween( \DB::raw('dayofyear(curdate()) - dayofyear(date_of_joining)'), [0,config('config.celebration_days')])
            ->orderBy('date_of_joining','asc')
            ->get();

        $celebrations = array();
        foreach($all_birthdays as $all_birthday){
            $number = date('Y') - date('Y',strtotime($all_birthday->date_of_birth));
            $celebrations[strtotime(date('d M',strtotime($all_birthday->date_of_birth)))] = array(
                'icon' => 'birthday-cake',
                'title' => getDateDiff($all_birthday->date_of_birth) ? : date('d M',strtotime($all_birthday->date_of_birth)),
                'date' => $all_birthday->date_of_birth,
                'number' => $number.'<sup>'.daySuffix($number).'</sup>'.' '.trans('messages.birthday'),
                'id' => $all_birthday->User->id,
                'name' => $all_birthday->User->full_name
            );
        }
        foreach($all_anniversaries as $all_anniversary){
            $number = date('Y') - date('Y',strtotime($all_anniversary->date_of_anniversary));
            $celebrations[strtotime(date('d M',strtotime($all_anniversary->date_of_anniversary)))] = array(
                'icon' => 'gift',
                'title' => getDateDiff($all_anniversary->date_of_anniversary) ? : date('d M',strtotime($all_anniversary->date_of_anniversary)),
                'date' => $all_anniversary->date_of_anniversary,
                'number' => $number.'<sup>'.daySuffix($number).'</sup>'.' '.trans('messages.anniversary'),
                'id' => $all_anniversary->User->id,
                'name' => $all_anniversary->User->full_name
            );
        }
        foreach($all_work_anniversaries as $all_work_anniversary){
            $number = date('Y') - date('Y',strtotime($all_work_anniversary->date_of_joining));
            if($number)
            $celebrations[strtotime(date('d M',strtotime($all_work_anniversary->date_of_joining)))] = array(
                'icon' => 'briefcase',
                'title' => getDateDiff($all_work_anniversary->date_of_joining) ? : date('d M',strtotime($all_work_anniversary->date_of_joining)),
                'date' => $all_work_anniversary->date_of_joining,
                'number' => $number.'<sup>'.daySuffix($number).'</sup>'.' '.trans('messages.work').' '.trans('messages.anniversary'),
                'id' => $all_work_anniversary->User->id,
                'name' => $all_work_anniversary->User->full_name
            );
        }

        ksort($celebrations);

        $child_designation = childDesignation(\Auth::user()->Profile->designation_id,1);
        $child_staff_count = \App\User::with('profile')->whereHas('profile',function($query) use($child_designation){
            $query->whereIn('designation_id',$child_designation);
        })->count();

        $tree = array();
        $designations = \App\Designation::all();
        foreach ($designations as $designation){
            $tree[$designation->id] = array(
                'parent_id' => $designation->top_designation_id,
                'name' => $designation->designation_with_department
            );
        }

        $my_shift = getShift();

        $assets = ['calendar','graph'];
        $menu = 'home';
        return view('home',compact('assets','celebrations','menu','announcements','child_staff_count','tree','my_shift'));
    }

    public function calendarEvents(Request $request){
        $first_day = $request->has('start') ? $request->input('start') : date('Y-m-01');
        $last_day  = $request->has('end') ? $request->input('end') : date('Y-m-t');
        $month = str_pad((date('m',strtotime($last_day)) - 1), 2, '0', STR_PAD_LEFT);

        $birthdays = \App\Profile::whereNotNull('date_of_birth')->orderBy('date_of_birth','asc')->get();

        $anniversaries = \App\Profile::whereNotNull('date_of_anniversary')->orderBy('date_of_anniversary','asc')->get();

        $user_employment = array();
        foreach(\App\User::whereStatus('active')->get() as $active_user){
            $user_employment_detail = getEmployment(date('Y-m-d'),$active_user->id);
            if($user_employment_detail)
                $user_employment[] = $user_employment_detail->id;
        }

        $work_anniversaries = \App\UserEmployment::whereIn('id',$user_employment)->where( \DB::raw('month(date_of_joining)'), [$month])
            ->get();

        $holidays = \App\Holiday::where('date','>=',$first_day)->where('date','<=',$last_day)->get();

        $todos = \App\Todo::where('user_id','=',\Auth::user()->id)
            ->where('date','>=',$first_day)
            ->where('date','<=',$last_day)
            ->orWhere(function ($query)  {
                $query->where('user_id','!=',\Auth::user()->id)
                    ->where('visibility','=','public');
            })->get();

        $events = array();

        $events[] = array('title' => trans('messages.today'), 'start' => date('Y-m-d'), 'color' => '#380000','icon' => 'user');

        foreach($birthdays as $birthday){
            $start = date('Y').'-'.date('m-d',strtotime($birthday->date_of_birth));
            $title = trans('messages.birthday').' : '.$birthday->User->full_name;
            $color = '#daa520';
            $events[] = array('title' => $title, 'start' => $start, 'color' => $color,'icon' => 'birthday-cake');
        }
        foreach($anniversaries as $anniversary){
            $start = date('Y').'-'.date('m-d',strtotime($anniversary->date_of_anniversary));
            $title = trans('messages.anniversary').' : '.$anniversary->User->full_name;
            $color = '#88b04b';
            $events[] = array('title' => $title, 'start' => $start, 'color' => $color,'icon' => 'gift');
        }
        foreach($work_anniversaries as $work_anniversary){
            $start = date('Y').'-'.date('m-d',strtotime($work_anniversary->date_of_joining));
            $title = ((date('Y',strtotime($work_anniversary->date_of_joining) != date('Y')) ) ? (trans('messages.work').' '.trans('messages.anniversary')) : (trans('messages.new').' '.trans('messages.joining')) ).' : '.$work_anniversary->User->full_name;
            $color = '#6dc066';
            $events[] = array('title' => $title, 'start' => $start, 'color' => $color,'icon' => 'briefcase');
        }
        foreach($todos as $todo){
            $start = $todo->date;
            $title = trans('messages.to_do').' : '.$todo->title.' '.$todo->description;
            $color = '#ff0000';
            $url = '/todo/'.$todo->id.'/edit';
            $events[] = array('title' => $title, 'start' => $start, 'color' => $color, 'url' => $url,'icon' => 'list-ul');
        }
        foreach($holidays as $holiday){
            $start = $holiday->date;
            $title = trans('messages.holiday').' : '.$holiday->description;
            $color = '#133edb';
            $events[] = array('title' => $title, 'start' => $start, 'color' => $color,'icon' => 'fighter-jet');
        }

        $tasks = \App\Task::whereHas('user',function($query){
            $query->where('user_id',\Auth::user()->id);
        })->orWhere('user_id',\Auth::user()->id)->get();

        foreach($tasks as $task){
            $events[] = array(
                'title' => trans('messages.task').' '.trans('messages.start').' '.trans('messages.date').' : '.$task->title, 
                'start' => $task->start_date, 
                'color' => '#50f442', 
                'url' => '/task/'.$task->id,
                'icon' => 'tasks');

            $events[] = array(
                'title' => trans('messages.task').' '.trans('messages.due').' '.trans('messages.date').' : '.$task->title, 
                'start' => $task->due_date, 
                'color' => '#f44242', 
                'url' => '/task/'.$task->id,
                'icon' => 'tasks');
        }

        return $events;
    }

    public function sidebar(Request $request){
        $menu = explode(',',$request->input('menu'));
        $inbox_count = \App\Message::whereToUserId(\Auth::user()->id)->whereDeleteReceiver('0')->whereIsRead(0)->count();
        $accessible_users = getAccessibleUserId();
        $ticket_count = \App\Ticket::whereStatus('open')->whereIn('user_id',$accessible_users)->count();
        $expense_count = \App\ExpenseStatusDetail::whereDesignationId(\Auth::user()->Profile->designation_id)->whereStatus('pending')->count();
        $leave_count = \App\LeaveStatusDetail::whereDesignationId(\Auth::user()->Profile->designation_id)->whereStatus('pending')->count();
        $task_count = \App\Task::whereHas('user',function($query){
            $query->where('user_id',\Auth::user()->id);
        })->where('progress','<',100)->count();
        return view('layouts.menu',compact('menu','inbox_count','ticket_count','expense_count','leave_count','task_count'))->render();
    }

    public function activityLog(){
        $table_data['activity-log-table'] = array(
            'source' => 'activity-log',
            'title' => 'Activity Log List',
            'id' => 'activity_log_table',
            'disable-sorting' => 1,
            'form' => 'activity-log-filter-form',
            'data' => array(
                'S No',
                trans('messages.user'),
                trans('messages.activity'),
                'IP',
                trans('messages.date'),
                'User Agent',
                )
            );

        $users = array();

        $assets = ['datatable'];
        return view('activity_log.index',compact('table_data','assets','users'));
    }

    public function activityLogList(Request $request){

        $query = \App\Activity::whereNotNull('id');
        if($request->has('user_id'))
            $query->whereUserId($request->input('user_id'));

        if($request->has('date_start') && $request->has('date_end'))
            $query->whereBetween('created_at',[$request->input('date_start').' 00:00:00',$request->input('date_end').' 23:59:59']);

        $activities = $query->orderBy('created_at','desc')->get();

        $rows = array();
        $i = 0;
        foreach($activities as $activity){
            $i++;

            $sub_module = ($activity->sub_module) ? '('.toWord($activity->sub_module).')' : '';

            if($activity->module == 'login' || $activity->module == 'logout')
                $activity_detail = trans('messages.'.$activity->activity);
            else
            $activity_detail = ($activity->activity == 'added') ? trans('messages.new').' '.toWord($activity->module).' '.$sub_module.' '.trans('messages.'.$activity->activity) : toWord($activity->module).' '.$sub_module.' '.trans('messages.'.$activity->activity);

            if($activity->login_as_user_id)
                $login_as_user_id = '('.trans('messages.login').' '.trans('messages.as').' '.$activity->LoginAsUser->full_name.')';
            else
                $login_as_user_id = '';

            $row = array(
                $i,
                $activity->User->full_name.' '.$login_as_user_id,
                $activity_detail,
                $activity->ip,
                showDateTime($activity->created_at),
                $activity->user_agent
                );

            $rows[] = $row;
        }

        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function lock(){
        if(session('locked'))
            return view('auth.lock');
        else
            return redirect('/home');
    }

    public function unlock(Request $request){
        if(!\Auth::check())
            return response()->json(['message' => trans('messages.session_expire'), 'status' => 'success','redirect' => '/login']);

        $validation = Validator::make($request->all(),[
            'password' => 'required'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $password = $request->input('password');

        if(\Hash::check($password,\Auth::user()->password)){
            session()->forget('locked');
            session()->put('last_activity',time());
            return response()->json(['status' => 'success','redirect' => '/home']);
        }

        return response()->json(['message' => trans('messages.unlock_failed'), 'status' => 'error']);
    }

    public function filter(Request $request){
        return response()->json(['message' => trans('messages.request').' '.trans('messages.submitted'), 'status' => 'success']);
    }

    public function demoRealTimeNotification(){
        if(!\Entrust::can('manage-configuration'))
            return redirect('/home')->withErrors(trans('messages.permission_denied'));

        if(!config('broadcasting.connections.pusher.key') || 
            !config('broadcasting.connections.pusher.secret') || 
            !config('broadcasting.connections.pusher.app_id') || 
            !config('broadcasting.connections.pusher.options.cluster')
            )
            return redirect('/home')->withErrors('Invalid Notification configuration.');

        $this->updateNotification(['module' => 'demo','module_id' => '1']);
        return view('demo_real_time_notification');
    }

    public function generateRealTimeNotification(Request $request){
        if(!\Entrust::can('manage-configuration'))
            return response()->json(['status' => 'error','redirect' => '/home','message' => trans('messages.permission_denied')]);

        if(!config('broadcasting.connections.pusher.key') || 
            !config('broadcasting.connections.pusher.secret') || 
            !config('broadcasting.connections.pusher.app_id') || 
            !config('broadcasting.connections.pusher.options.cluster')
            )
            return response()->json(['status' => 'error','redirect' => '/home','message' => 'Invalid Notification configuration.']);

        $users = \App\User::all()->pluck('id')->all();
        $this->sendNotification(['module' => 'demo','module_id' => '1','url' => '/demo-real-time-notification','user' => implode(',',$users)]);
        return response()->json(['message' => 'Real Time Notification sent to all users!', 'status' => 'success']);
    }
}
