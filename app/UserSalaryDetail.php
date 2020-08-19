<?php
namespace App;
use Eloquent;

class UserSalaryDetail extends Eloquent {

	protected $fillable = [
							'user_salary_id',
							'salary_head_id',
							'amount'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_salary_details';

	public function userSalary()
    {
        return $this->belongsTo('App\UserSalary');
    }

	public function salaryHead()
    {
        return $this->belongsTo('App\SalaryHead');
    }
}
