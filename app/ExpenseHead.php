<?php
namespace App;
use Eloquent;

class ExpenseHead extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'expense_heads';
}
