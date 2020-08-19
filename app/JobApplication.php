<?php
namespace App;
use Eloquent;

class JobApplication extends Eloquent {

	protected $fillable = [
							'job_id',
							'first_name',
							'last_name',
							'email',
							'primary_contact_number',
							'secondary_contact_number',
							'date_of_birth',
							'gender',
							'address_line_1',
							'address_line_2',
							'city',
							'state',
							'zipcode',
							'country_id',
							'additional_information'
						];
	protected $primaryKey = 'id';
	protected $table = 'job_applications';

	public function job()
    {
        return $this->belongsTo('App\Job','job_id');
    }

    public function getFullNameAttribute(){
    	return $this->first_name.' '.$this->last_name;
    }

    public function applicantUser(){
    	return $this->belongsTo('App\User','applicant_user_id');
    }

    public function jobApplicationStatusDetail(){
    	return $this->hasMany('App\JobApplicationStatusDetail');
    }
}
