<?php
namespace App;
use Eloquent;

class Expense extends Eloquent {

	protected $fillable = [
						'expense_head_id',
						'date_of_expense',
						'currency_id',
						'amount',
						'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'expenses';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function expenseHead()
    {
        return $this->belongsTo('App\ExpenseHead');
    }

	public function expenseStatusDetail()
    {
        return $this->hasMany('App\ExpenseStatusDetail','expense_id');
    }

	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
}
