<?php
namespace App;
use Eloquent;

class SalaryHead extends Eloquent {

	protected $fillable = [
							'name',
							'is_fixed',
							'type',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'salary_heads';
}
