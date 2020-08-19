<?php
namespace App;
use Eloquent;

class JobApplicationStatusDetail extends Eloquent {

	protected $fillable = [
							'job_application_id',
							'status',
							'remarks'
						];
	protected $primaryKey = 'id';
	protected $table = 'job_application_status_details';

	public function jobApplication()
    {
        return $this->belongsTo('App\JobApplication','job_application_id');
    }

    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
}
