<?php
namespace App;
use Eloquent;

class TaskAttachment extends Eloquent {

	protected $fillable = [
							'title',
							'description',
							'task_id',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'task_attachments';

	public function task()
    {
        return $this->belongsTo('App\Task');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
