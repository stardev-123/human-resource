<?php
namespace App;
use Eloquent;

class LeaveStatusDetail extends Eloquent {

	protected $fillable = [
						'leave_id',
						'designation_id',
						'status',
						'remarks'
						];
	protected $primaryKey = 'id';
	protected $table = 'leave_status_details';

	public function leave()
    {
        return $this->belongsTo('App\Leave');
    }

	public function designation()
    {
        return $this->belongsTo('App\Designation');
    }
}
