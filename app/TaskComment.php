<?php
namespace App;
use Eloquent;

class TaskComment extends Eloquent {

	protected $fillable = [
							'task_id',
							'comment',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'task_comments';

	public function task()
    {
        return $this->belongsTo('App\Task');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
