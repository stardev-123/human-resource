<?php
namespace App;
use Eloquent;

class UserEmployment extends Eloquent {

	protected $fillable = [
							'date_of_joining',
							'remarks',
							'date_of_leaving',
							'leaving_remarks',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_employments';

	public function user() {
    	return $this->belongsTo('App\User');
	}
}
