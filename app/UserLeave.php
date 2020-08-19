<?php
namespace App;
use Eloquent;

class UserLeave extends Eloquent {

	protected $fillable = [
							'user_id',
							'from_date',
							'to_date',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_leaves';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function userLeaveDetail()
    {
        return $this->hasMany('App\UserLeaveDetail');
    }
}
