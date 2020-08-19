<?php
namespace App;
use Eloquent;

class Leave extends Eloquent {

	protected $fillable = [
						'leave_type_id',
						'from_date',
						'to_date',
						'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'leaves';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function leaveType()
    {
        return $this->belongsTo('App\LeaveType');
    }

	public function leaveStatusDetail()
    {
        return $this->hasMany('App\LeaveStatusDetail','leave_id');
    }
}
