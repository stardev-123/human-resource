<?php
namespace App;
use Eloquent;

class StarredTask extends Eloquent {

	protected $fillable = [
							'task_id',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'starred_tasks';
    public $timestamps = false;

	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
