<?php
namespace App;
use Eloquent;

class UserExperience extends Eloquent {

	protected $fillable = [
							'company_name',
							'company_address',
							'company_contact_number',
							'company_website',
							'from_date',
							'to_date',
							'job_title',
							'description',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_experiences';

	public function user() {
    	return $this->belongsTo('App\User');
	}
}
