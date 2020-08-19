<?php
namespace App;
use Eloquent;

class Payroll extends Eloquent {

	protected $fillable = [
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'payrolls';

	
    public function user()
    {
        return $this->belongsTo('App\User'); 
    }

    public function payrollDetail()
    {
        return $this->hasMany('App\PayrollDetail'); 
    }
}
