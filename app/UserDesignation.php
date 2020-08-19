<?php
namespace App;
use Eloquent;

class UserDesignation extends Eloquent {

	protected $fillable = [
							'designation_id',
							'description',
							'from_date',
							'to_date',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_designations';

	public function user() {
    	return $this->belongsTo('App\User');
	}

	public function designation() {
    	return $this->belongsTo('App\Designation');
	}
}
