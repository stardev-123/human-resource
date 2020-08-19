<?php
namespace App;
use Eloquent;

class LeaveType extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'leave_types';
}
