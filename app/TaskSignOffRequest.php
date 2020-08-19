<?php
namespace App;
use Eloquent;

class TaskSignOffRequest extends Eloquent {

	protected $fillable = [
							'task_id',
							'user_id',
							'status',
							'remarks'
						];
	protected $primaryKey = 'id';
	protected $table = 'task_signoff_requests';

	public function task()
    {
        return $this->belongsTo('App\Task');
    }

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
