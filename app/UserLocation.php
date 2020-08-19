<?php
namespace App;
use Eloquent;

class UserLocation extends Eloquent {

	protected $fillable = [
							'location_id',
							'description',
							'from_date',
							'to_date',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_locations';

	public function user() {
    	return $this->belongsTo('App\User');
	}

	public function location() {
    	return $this->belongsTo('App\Location');
	}
}
