<?php
namespace App;
use Eloquent;

class Activity extends Eloquent {

	protected $fillable = [
							'user_id',
							'login_as_user_id',
							'module',
							'sub_module',
							'user_agent',
							'module_id',
							'sub_module_id',
							'activity',
							'ip'
						];
	protected $primaryKey = 'id';
	protected $table = 'activities';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function loginAsUser()
    {
        return $this->belongsTo('App\User','login_as_user_id');
    }
}
