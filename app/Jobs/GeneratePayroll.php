<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PDF;

class GeneratePayroll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    use \App\Http\Controllers\BasicController;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $from_date;
    protected $to_date;
    protected $send_mail;

    public function __construct($from_date,$to_date,$send_mail)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->send_mail = $send_mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $salary_heads = \App\SalaryHead::all();
        $leave_types = \App\LeaveType::all();
        $earning_salary_heads = \App\SalaryHead::where('type','=','earning')->get();
        $deduction_salary_heads = \App\SalaryHead::where('type','=','deduction')->get();

        $from_date_month = date('m',strtotime($this->from_date));
        $to_date_month = date('m',strtotime($this->to_date));
        $from_date_year = date('Y',strtotime($this->from_date));
        $to_date_year = date('Y',strtotime($this->to_date));

        if($from_date_month != $to_date_month){
          $payroll_days = (config('config.payroll_days') == 'start_date') ? cal_days_in_month(CAL_GREGORIAN, $from_date_month, $from_date_year) : cal_days_in_month(CAL_GREGORIAN, $to_date_month, $to_date_year);
        } else
          $payroll_days = cal_days_in_month(CAL_GREGORIAN, $from_date_month, $from_date_year);

        foreach(\App\User::all() as $user){
            $count = \App\Payroll::whereUserId($user->id)->
            where(function ($query) { $query->where(function ($query) {
              $query->where('from_date','>=',$this->from_date)
              ->where('from_date','<=',$this->to_date);
            })->orWhere(function ($query) {
              $query->where('to_date','>=',$this->from_date)
                ->where('to_date','<=',$this->to_date);
            });})->count();

            if(!$count){
                $user_salary = getUserSalary($this->from_date,$user->id);
                if(isset($user_salary) && $user_salary->to_date >= $this->to_date){
                    $data = $this->getAttendanceSummary($user,$this->from_date,$this->to_date);
                    $att_summary = $data['att_summary'];
                    $summary = $data['summary'];
                    $total = $data['total'];
                    $working_day = $att_summary['P'] + $att_summary['L'] + $att_summary['H'];
                    $half_days = $att_summary['HD'];

                    $payroll = new \App\Payroll;
                    $payroll->user_id = $user->id;
                    $payroll->uuid = getUuid();
                    $payroll->currency_id = $user_salary->currency_id;
                    $payroll->from_date = $this->from_date;
                    $payroll->to_date = $this->to_date;
                    $payroll->date_of_payroll = date('Y-m-d');

                    $payroll->hourly = ($user_salary->type == 'hourly') ? (floor($total['total_working'] / 3600) * $user_salary->hourly_rate) : 0;
                    $payroll->late = ($user_salary->type == 'monthly') ? (floor($total['total_late'] / 3600) * $user_salary->late_hourly_rate) : 0;
                    $payroll->overtime = ($user_salary->type == 'monthly') ? (floor($total['total_overtime'] / 3600) * $user_salary->overtime_hourly_rate) : 0;
                    $payroll->early_leaving = ($user_salary->type == 'monthly') ? (floor($total['total_early_leaving'] / 3600) * $user_salary->early_leaving_hourly_rate) : 0;
                    $payroll->is_hourly = ($user_salary->type == 'monthly') ? 0 : 1;
                    $payroll->save();

                    if(!$payroll->is_hourly){
                        foreach($salary_heads as $salary_head){
                            $salary = ($user_salary->UserSalaryDetail->where('salary_head_id',$salary_head->id)->count()) ? ($user_salary->UserSalaryDetail->where('salary_head_id',$salary_head->id)->first()->amount) : 0;
                            if(!$salary_head->is_fixed)
                                $salary = ($salary/$payroll_days)*$working_day + ((($salary/$payroll_days)*$half_days)/2);
                            $payroll_detail = new \App\PayrollDetail;
                            $payroll_detail->payroll_id = $payroll->id;
                            $payroll_detail->salary_head_id = $salary_head->id;
                            $payroll_detail->amount = ($user_salary->type == 'monthly') ? $salary : 0;
                            $payroll_detail->save();
                        }
                    }
                    $this->sendNotification(['module' => 'payroll','module_id' => $payroll->id,'url' => '/payroll/'.$payroll->uuid,'user' => $payroll->user_id,'action' => 'create-payroll']);

                    if($this->send_mail) {
                        
                        $payroll_details = $payroll->PayrollDetail->pluck('amount','salary_head_id')->all();
                        $user_leave = \App\UserLeave::whereUserId($payroll->user_id)->where('from_date','<=',$payroll->from_date)->where('to_date','>=',$payroll->from_date)->first();
                        $user_leave_data = array();
                        
                        if($user_leave){
                          foreach($leave_types as $leave_type){
                            $leave_detail = $user_leave->UserLeaveDetail->where('leave_type_id',$leave_type->id)->first();
                            $leave_used = ($leave_detail) ? $leave_detail->leave_used : 0;
                            $leave_assigned = ($leave_detail) ? $leave_detail->leave_assigned : 0;
                            $user_leave_data[$leave_type->id] = array(
                              'leave_used' => $leave_used,
                              'leave_assigned' => $leave_assigned
                            );
                          }
                        }

                        $data = [
                            'user' => $user,
                            'payroll' => $payroll,
                            'earning_salary_heads' => $earning_salary_heads,
                            'deduction_salary_heads' => $deduction_salary_heads,
                            'payroll_details' => $payroll_details,
                            'total_earning' => 0,
                            'total_deduction' => 0,
                            'summary' => $summary,
                            'att_summary' => $att_summary,
                            'leave_types' => $leave_types,
                            'company_address' => $this->getCompanyAddress(),
                            'user_leave_data' => $user_leave_data
                        ];

                        $mail_data = $this->templateContent(['slug' => 'payroll','payroll' => $payroll,'user' => $user]);
                        if(count($mail_data)){
                            $pdf = \PDF::loadView('payroll.print',$data);
                            $mail['email'] = $user->email;
                            $mail['subject'] = $mail_data['subject'];
                            $mail['filename'] = $payroll->User->fullname.'.pdf';
                            $body = $mail_data['body'];

                            \Mail::send('emails.email', compact('body'), function ($message) use($mail,$pdf) {
                              $message->attachData($pdf->output(), $mail['filename']);
                              $message->to($mail['email'])->subject($mail['subject']);
                            });
                            $this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body,'module' => 'payroll','module_id' =>$payroll->id));
                        }
                    }
                }
            }
        }
    }
}
