<?php
namespace App;
use Eloquent;

class ExpenseStatusDetail extends Eloquent {

	protected $fillable = [
						'expense_id',
						'designation_id',
						'status',
						'remarks'
						];
	protected $primaryKey = 'id';
	protected $table = 'expense_status_details';

	public function expense()
    {
        return $this->belongsTo('App\Expense');
    }

	public function designation()
    {
        return $this->belongsTo('App\Designation');
    }
}
