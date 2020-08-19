<?php
namespace App;
use Eloquent;

class SubTask extends Eloquent {

	protected $fillable = [
							'task_id',
							'title',
                            'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'sub_tasks';

	public function task()
    {
        return $this->belongsTo('App\Task');
    }

	public function subTaskRating()
    {
        return $this->hasMany('App\SubTaskRating');
    }

    public function userAdded(){
    	return $this->belongsTo('App\User','user_id');
    }
}
