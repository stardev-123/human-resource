<?php
namespace App;
use Eloquent;

class Job extends Eloquent {

	protected $fillable = [
							'title',
							'gender',
							'contract_type_id',
							'date_of_closing',
							'no_of_post',
							'location_id',
							'publish_portal',
							'designation_id',
							'age_info',
							'start_age',
							'end_age',
							'salary_info',
							'currency_id',
							'start_salary',
							'end_salary',
							'qualification',
							'experience',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'jobs';

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }

	public function contractType()
    {
        return $this->belongsTo('App\ContractType');
    }

	public function location()
    {
        return $this->belongsTo('App\Location');
    }

	public function designation()
    {
        return $this->belongsTo('App\Designation');
    }

    public function getLocationNameAttribute(){
    	return ($this->location_id) ? $this->Location->name : '';
    }

    public function getDesignationNameAttribute(){
    	return ($this->designation_id) ? $this->Designation->name.' ('.$this->Designation->Department->name.')' : '';
    }

    public function jobApplication(){
    	return $this->hasMany('App\JobApplication');
    }

    public function getJobUrlAttribute(){
    	return url('/jobs/'.createSlug($this->title).'/'.$this->uuid);
    }
}
