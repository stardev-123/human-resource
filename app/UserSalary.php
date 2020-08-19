<?php
namespace App;
use Eloquent;

class UserSalary extends Eloquent {

	protected $fillable = [
							'user_id',
							'type',
							'currency_id',
							'from_date',
							'to_date',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_salaries';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function userSalaryDetail()
    {
        return $this->hasMany('App\UserSalaryDetail');
    }

	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
