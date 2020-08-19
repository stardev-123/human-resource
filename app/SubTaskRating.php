<?php
namespace App;
use Eloquent;

class SubTaskRating extends Eloquent {

	protected $fillable = [
							'sub_task_id',
							'user_id',
                            'rating',
                            'comment'
						];
	protected $primaryKey = 'id';
	protected $table = 'sub_task_ratings';

	protected function subTask()
    {
        return $this->belongsTo('App\SubTask');
    }

	protected function user()
    {
        return $this->belongsTo('App\User');
    }
}
