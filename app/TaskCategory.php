<?php
namespace App;
use Eloquent;

class TaskCategory extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'task_categories';
}
