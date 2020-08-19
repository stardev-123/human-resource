<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Notifications\NewNotification;

trait BasicController {

    public function recaptchaResponse($request){
        if($request->has('g-recaptcha-response'))
            return ['success' => true];
        else
            return ['success' => false];
    }

    public function sendNotification($data){
        if(!config('config.enable_push_notification'))
            return;

        if(!in_array($data['module'],explode(',',config('config.push_notification_modules'))) && $data['module'] != 'demo')
            return;

        $user = \Auth::user();
        $auth_user_full_name = $user->full_name;

        if($data['module'] == 'demo')
            $description = trans('messages.notification_demo',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'message')
            $description = trans('messages.notification_sent_a_message',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'ticket' && $data['action'] == 'create-ticket')
            $description = trans('messages.notification_raised_a_ticket',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'ticket' && $data['action'] == 'reply-ticket')
            $description = trans('messages.notification_replied_to_ticket',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'ticket' && $data['action'] == 'close-ticket')
            $description = trans('messages.notification_closed_a_ticket',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'award')
            $description = trans('messages.notification_received_an_award',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'library')
            $description = trans('messages.notification_published_an_library',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'announcement')
            $description = trans('messages.notification_published_an_announcement',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'daily-report')
            $description = trans('messages.notification_submitted_daily_report',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'expense' && $data['action'] == 'create-expense')
            $description = trans('messages.notification_submitted_an_expense',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'expense' && $data['action'] == 'reject-expense')
            $description = trans('messages.notification_rejected_your_expense',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'expense' && $data['action'] == 'partially-approve-expense')
            $description = trans('messages.notification_partially_approved_your_expense',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'expense' && $data['action'] == 'approve-expense')
            $description = trans('messages.notification_approved_your_expense',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'leave' && $data['action'] == 'request-leave')
            $description = trans('messages.notification_requested_a_leave',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'leave' && $data['action'] == 'reject-leave')
            $description = trans('messages.notification_rejected_your_leave',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'leave' && $data['action'] == 'partially-approve-leave')
            $description = trans('messages.notification_partially_approved_your_leave',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'leave' && $data['action'] == 'approve-leave')
            $description = trans('messages.notification_approved_your_leave',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'assign-task')
            $description = trans('messages.notification_assigned_a_task',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'create-task')
            $description = trans('messages.notification_created_a_task',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'request-task-sign-off')
            $description = trans('messages.notification_requested_task_sign_off',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'approve-task-sign-off')
            $description = trans('messages.notification_approved_task_sign_off',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'reject-task-sign-off')
            $description = trans('messages.notification_rejected_task_sign_off',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'task' && $data['action'] == 'cancel-task-sign-off')
            $description = trans('messages.notification_cancelled_task_sign_off',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'payroll' && $data['action'] == 'create-payroll')
        $description = trans('messages.notification_generated_payroll',['name' => $auth_user_full_name]);
        elseif($data['module'] == 'payroll' && $data['action'] == 'update-payroll')
        $description = trans('messages.notification_updated_payroll',['name' => $auth_user_full_name]);

        if($data['user'] && isset($description)){
            $notification = \App\Notification::create([
                'user_id' => \Auth::user()->id,
                'user' => $data['user'],
                'uuid' => \Uuid::generate(),
                'module' => $data['module'],
                'module_id' => $data['module_id'],
                'description' => $description,
                'user_read' => '',
                'url' => $data['url']
            ]);
            event(new \App\Events\PushEvent($notification->id));
            if(getMode())
            foreach(explode(',',$data['user']) as $user_id){
                $user = \App\User::find($user_id);
                $user->notify(new NewNotification($notification,$user));
            }
        }
    }

    public function updateNotification($data){
        if(!config('config.enable_push_notification'))
            return;

        $notification = \App\Notification::whereModuleId($data['module_id'])->whereModule($data['module'])->whereRaw('FIND_IN_SET(?,user)', [\Auth::user()->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [\Auth::user()->id])->first();

        if($notification)
            updateReadNotification($notification);
    }

    public function logActivity($data) {

        if(session()->has('parent_login')){
            $data['login_as_user_id'] = isset($data['user_id']) ? $data['user_id'] : ((\Auth::check()) ? \Auth::user()->id : null);
            $data['user_id'] = session('parent_login');
        } else
            $data['user_id'] = isset($data['user_id']) ? $data['user_id'] : ((\Auth::check()) ? \Auth::user()->id : null);

        $data['ip'] = \Request::getClientIp();
        $data['module'] = isset($data['module']) ? $data['module'] : null;
        $data['module_id'] = isset($data['module_id']) ? $data['module_id'] : null;
        $data['sub_module'] = isset($data['sub_module']) ? $data['sub_module'] : null;
        $data['sub_module_id'] = isset($data['sub_module_id']) ? $data['sub_module_id'] : null;
        $data['user_agent'] = \Request::header('User-Agent');
        if(config('config.enable_activity_log'))
        $activity = \App\Activity::create($data);
    }

    public function logEmail($data){
        $data['to_address'] = $data['to'];
        unset($data['to']);
        $data['from_address'] = config('mail.from.address');
        $data['module'] = isset($data['module']) ? $data['module'] : null;
        $data['module_id'] = isset($data['module_id']) ? $data['module_id'] : null;
        if(config('config.enable_email_log'))
        \App\Email::create($data);
    }

    public function getSetupGuide($response, $menu = null){
        if($menu && \App\Setup::whereModule($menu)->whereCompleted(0)->first())
            \App\Setup::whereModule($menu)->whereCompleted(0)->update(['completed' => 1]);

        if(config('config.setup_guide') && defaultRole()){
            $setup_guide = setupGuide();
            $response['setup_guide'] = $setup_guide;
        }
        return $response;
    }

    public function getCompanyAddress(){
        $company_address = config('config.company_address_line_1');
        $company_address .= (config('config.company_address_line_2')) ? (config('config.company_address_line_2')) : '';
        $company_address .= (config('config.company_city')) ? ', <br >'.(config('config.company_city')) : '';
        $company_address .= (config('config.company_state')) ? ', '.(config('config.company_state')) : '';
        $company_address .= (config('config.company_zipcode')) ? ', '.(config('config.company_zipcode')) : '';
        $company_address .= (config('config.company_country_id')) ? '<br >'.(config('country.'.config('config.company_country_id'))) : '';

        return $company_address;
    }

    public function designationAccessible($designation){
        if(Entrust::can('manage-all-designation') || (Entrust::can('manage-subordinate-designation') && isChild($designation->id)))
            return 1;
        else
            return 0;
    }

    public function userAccessible($user,$self = 0){
        if(defaultRole())
            return 1;

        if(in_array($user->id, getAccessibleUserId(\Auth::user()->id,$self)))
            return 1;
        else
            return 0;
    }

    public function fetchTask(){
        $users = getAccessibleUserId(\Auth::user()->id,1);
        $query = \App\Task::where(function($q) use($users){
            $q->whereHas('user', function($q1) use($users){
                $q1->whereIn('user_id',$users);
            })->orWhere('user_id','=',\Auth::user()->id);
        });

        return $query;
    }

    public function taskAccessible($task_id){
        $query = $this->fetchTask();
        $tasks = $query->get()->pluck('id')->all();
        if(in_array($task_id, $tasks))
            return 1;
        else
            return 0;
    }

    public function templateContent($data){
        $template = \App\Template::whereSlug($data['slug'])->first();
        $mail_data = array();
        if(!$template)
            return $mail_data;

        $body = $template->body;
        $subject = $template->subject;

        $company_address = $this->getCompanyAddress();

        $company_logo = getCompanyLogo();
        $body = str_replace('[COMPANY_LOGO]',$company_logo,$body);

        $body = str_replace('[COMPANY_NAME]',config('config.company_name'),$body);
        $subject = str_replace('[COMPANY_NAME]',config('config.company_name'),$subject);

        $body = str_replace('[COMPANY_EMAIL]',config('config.company_email'),$body);
        $subject = str_replace('[COMPANY_EMAIL]',config('config.company_email'),$subject);

        $body = str_replace('[COMPANY_PHONE]',config('config.company_phone'),$body);
        $subject = str_replace('[COMPANY_PHONE]',config('config.company_phone'),$subject);

        $body = str_replace('[COMPANY_WEBSITE]',config('config.company_website'),$body);
        $subject = str_replace('[COMPANY_WEBSITE]',config('config.company_website'),$subject);

        $body = str_replace('[COMPANY_ADDRESS]',$company_address,$body);
        $subject = str_replace('[COMPANY_ADDRESS]',$company_address,$subject);

        $body = str_replace('[CURRENT_DATE]',showDate(date('Y-m-d')),$body);
        $subject = str_replace('[CURRENT_DATE]',showDate(date('Y-m-d')),$subject);

        $body = str_replace('[CURRENT_DATE_TIME]',showDateTime(date('Y-m-d H:i:s')),$body);
        $subject = str_replace('[CURRENT_DATE_TIME]',showDateTime(date('Y-m-d H:i:s')),$subject);

        if($template->category == 'user' || $template->category == 'payroll'){
            if(array_key_exists('user', $data)){
                $user = $data['user'];

                $body = str_replace('[NAME]',($user->full_name) ? : '-',$body);
                $subject = str_replace('[NAME]',($user->full_name) ? : '-',$subject);

                $body = str_replace('[USERNAME]',$user->username,$body);
                $subject = str_replace('[USERNAME]',$user->username,$subject);

                $body = str_replace('[EMAIL]',$user->email,$body);
                $subject = str_replace('[EMAIL]',$user->email,$subject);

                $body = str_replace('[DEPARTMENT]',$user->department_name,$body);
                $subject = str_replace('[DEPARTMENT]',$user->department_name,$subject);

                $body = str_replace('[DESIGNATION]',$user->designation_name,$body);
                $subject = str_replace('[DESIGNATION]',$user->designation_name,$subject);

                $body = str_replace('[LOCATION]',$user->location_name,$body);
                $subject = str_replace('[LOCATION]',$user->location_name,$subject);
            }
        }

        if($template->category == 'user'){
            if(array_key_exists('user', $data)){
                $user = $data['user'];
                $password = (array_key_exists('password', $data)) ? $data['password'] : '';
                $body = str_replace('[PASSWORD]',$password,$body);
                $subject = str_replace('[PASSWORD]',$password,$subject);

                $body = str_replace('[DATE_OF_BIRTH]',($user->Profile) ? showDate($user->Profile->date_of_birth) : '-',$body);
                $subject = str_replace('[DATE_OF_BIRTH]',($user->Profile) ? showDate($user->Profile->date_of_birth) : '-',$subject);

                $body = str_replace('[DATE_OF_ANNIVERSARY]',($user->Profile) ? showDate($user->Profile->date_of_anniversary) : '-',$body);
                $subject = str_replace('[DATE_OF_ANNIVERSARY]',($user->Profile) ? showDate($user->Profile->date_of_anniversary) : '-',$subject);
            }
        }

        if($template->category == 'payroll'){
            if(array_key_exists('payroll', $data)){
                $payroll = $data['payroll'];

                $body = str_replace('[DATE_OF_PAYROLL]',($payroll->date_of_payroll) ? showDate($payroll->date_of_payroll) : '-',$body);
                $subject = str_replace('[DATE_OF_PAYROLL]',($payroll->date_of_payroll) ? showDate($payroll->date_of_payroll) : '-',$subject);

                $body = str_replace('[PAYROLL_FROM_DATE]',($payroll->from_date) ? showDate($payroll->from_date) : '-',$body);
                $subject = str_replace('[PAYROLL_FROM_DATE]',($payroll->from_date) ? showDate($payroll->from_date) : '-',$subject);

                $body = str_replace('[PAYROLL_TO_DATE]',($payroll->to_date) ? showDate($payroll->to_date) : '-',$body);
                $subject = str_replace('[PAYROLL_TO_DATE]',($payroll->to_date) ? showDate($payroll->to_date) : '-',$subject);
            }
        }

        $mail_data['body'] = $body;
        $mail_data['subject'] = $subject;
        return $mail_data;
    }

    public function attendanceLabel($attendance){
        if($attendance == 'P')
            return '<span class="badge badge-success">'.trans('messages.present').'</span>';
        elseif($attendance == 'HD')
            return '<span class="badge badge-primary">'.trans('messages.half').' '.trans('messages.day').'</span>';
        elseif($attendance == 'L')
            return '<span class="badge badge-warning">'.trans('messages.leave').'</span>';
        elseif($attendance == 'H')
            return '<span class="badge badge-info">'.trans('messages.holiday').'</span>';
        elseif($attendance == 'A')
            return '<span class="badge badge-danger">'.trans('messages.absent').'</span>';
        elseif($attendance == '')
            return;
    }

    public function attendanceTag($attendance){
        if($attendance == 'overtime')
            return '<span class="badge badge-success" data-toggle="tooltip" title="'.trans('messages.overtime').'">O</span> ';
        elseif($attendance == 'late')
            return '<span class="badge badge-danger" data-toggle="tooltip" title="'.trans('messages.late').'">L</span> ';
        elseif($attendance == 'early_leaving')
            return '<span class="badge badge-warning" data-toggle="tooltip" title="'.trans('messages.early_leaving').'">E</span> ';
        else
            return;
    }

    public function getAttendanceSummary($user,$from_date,$to_date){

        $clocks = \App\Clock::whereUserId($user->id)->where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $holidays = \App\Holiday::where('date','>=',$from_date)->where('date','<=',$to_date)->get();
        $leaves = \App\Leave::whereStatus('approved')->whereUserId($user->id)->get();

        $leave_approved = array();
        $half_day_leave_approved = array();
        foreach($leaves as $leave){
            $leave_date_approved = ($leave->date_approved) ? explode(',',$leave->date_approved) : [];
            foreach($leave_date_approved as $date_approved){
                if($leave->is_half_day)
                    $half_day_leave_approved[] = $date_approved;
                else
                    $leave_approved[] = $date_approved;
            }
        }

        $total_late = $total_early_leaving = $total_overtime = $total_working = $total_rest = 0;
        $date = $from_date;
        $cols_summary = array();
        $tag_count = array();
        while($date <= $to_date){
            $tag = '';
            $late = $early_leaving = $overtime = $working = $rest = 0;

            $user_shift = getShift($date,$user->id);
            $user_shift->in_time = $date.' '.$user_shift->in_time;
            $user_shift->out_time = ($user_shift->overnight) ? date('Y-m-d',strtotime($date . ' +1 days')).' '.$user_shift->out_time : $date.' '.$user_shift->out_time;

            $out = $clocks->where('date',$date)->sortBy('clock_in')->last();
            $in = $clocks->where('date',$date)->sortBy('clock_in')->first();
            $records = $clocks->where('date',$date)->all();

            $late = (isset($in) && (strtotime($in->clock_in) > strtotime($user_shift->in_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->in_time) - strtotime($in->clock_in)) : 0;
            if($late){
                $tag .= $this->attendanceTag('late');
                $tag_count[] = 'L';
            }
            $total_late += $late;

            $early_leaving = (isset($out) && $out->clock_out != null && (strtotime($out->clock_out) < strtotime($user_shift->out_time)) && $user_shift->in_time != $user_shift->out_time) ? abs(strtotime($user_shift->out_time) - strtotime($out->clock_out)) : 0;
            if($early_leaving){
                $tag .= $this->attendanceTag('early_leaving');
                $tag_count[] = 'E';
            }
            $total_early_leaving += $early_leaving;

            foreach($records as $record){
                if($record->clock_in >= $user_shift->out_time && $record->clock_out != null)
                    $overtime += strtotime($record->clock_out) - strtotime($record->clock_in);
                elseif($record->clock_in < $user_shift->out_time && $record->clock_out > $user_shift->out_time)
                    $overtime += strtotime($record->clock_out) - strtotime($user_shift->out_time);
            }
            if($overtime){
                $tag .= $this->attendanceTag('overtime');
                $tag_count[] = 'O';
            }
            $total_overtime += $overtime;

            foreach($records as $record)
                $working += ($record->clock_out != null) ? abs(strtotime($record->clock_out) - strtotime($record->clock_in)) : 0;
            $total_working += $working;

            $rest = (isset($in) && $out->clock_out != null) ? (abs(strtotime($out->clock_out) - strtotime($in->clock_in)) - $working) : 0;
            $total_rest += $rest;

            $holiday = $holidays->where('date',$date)->first();

            if(isset($in))
                $attendance = 'P';
            elseif(count($half_day_leave_approved) && in_array($date,$half_day_leave_approved))
                $attendance = 'HD';
            elseif(count($leave_approved) && in_array($date,$leave_approved))
                $attendance = 'L';
            elseif($holiday)
                $attendance = 'H';
            elseif(!$holiday && $date < date('Y-m-d'))
                $attendance = 'A';
            else
                $attendance = '';

            $cols_summary[$date] = $attendance;
            $date = date('Y-m-d',strtotime($date . ' +1 days'));
        }

        $total['total_late'] = $total_late;
        $total['total_early_leaving'] = $total_early_leaving;
        $total['total_working'] = $total_working;
        $total['total_rest'] = $total_rest;
        $total['total_overtime'] = $total_overtime;

        $summary['total_late'] = showDuration($total_late);
        $summary['total_early_leaving'] = showDuration($total_early_leaving);
        $summary['total_working'] = showDuration($total_working);
        $summary['total_rest'] = showDuration($total_rest);
        $summary['total_overtime'] = showDuration($total_overtime);

        $cols_summary = array_count_values($cols_summary);
        $tag_summary = array_count_values($tag_count);

        $att_summary['A'] = array_key_exists('A', $cols_summary) ? $cols_summary['A'] : 0;
        $att_summary['H'] = array_key_exists('H', $cols_summary) ? $cols_summary['H'] : 0;
        $att_summary['P'] = array_key_exists('P', $cols_summary) ? $cols_summary['P'] : 0;
        $att_summary['HD'] = array_key_exists('HD', $cols_summary) ? $cols_summary['HD'] : 0;
        $att_summary['L'] = array_key_exists('L', $cols_summary) ? $cols_summary['L'] : 0;
        $att_summary['Late'] = array_key_exists('L', $tag_summary) ? $tag_summary['L'] : 0;
        $att_summary['Early'] = array_key_exists('E', $tag_summary) ? $tag_summary['E'] : 0;
        $att_summary['Overtime'] = array_key_exists('O', $tag_summary) ? $tag_summary['O'] : 0;
        $att_summary['W'] = $att_summary['H'] + $att_summary['P'];

        return ['summary' => $summary,'att_summary' => $att_summary,'total' => $total];
    }
}
