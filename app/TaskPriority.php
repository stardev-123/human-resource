<?php
namespace App;
use Eloquent;

class TaskPriority extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'task_priorities';
}
