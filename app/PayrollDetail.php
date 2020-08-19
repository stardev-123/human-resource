<?php
namespace App;
use Eloquent;

class PayrollDetail extends Eloquent {

	protected $fillable = [
							'payroll_id',
							'salary_head_id',
							'amount'
						];
	protected $primaryKey = 'id';
	protected $table = 'payroll_details';

    public function payroll()
    {
        return $this->belongsTo('App\Payroll'); 
    }

    public function salaryHead()
    {
        return $this->belongsTo('App\SalaryHead'); 
    }
}
