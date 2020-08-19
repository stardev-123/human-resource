<?php
namespace App;
use Eloquent;

class UserLeaveDetail extends Eloquent {

	protected $fillable = [
							'user_leave_id',
							'leave_type_id',
							'leave_assigned',
							'leave_used'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_leave_details';

	public function userLeave()
    {
        return $this->belongsTo('App\UserLeave');
    }

	public function leaveType()
    {
        return $this->belongsTo('App\LeaveType');
    }
}
