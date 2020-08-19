<?php
namespace App;
use Eloquent;

class Task extends Eloquent {

	protected $fillable = [
							'title',
							'description',
							'start_date',
							'due_date',
                            'task_category_id',
                            'task_priority_id',
                            'tags'
						];
	protected $primaryKey = 'id';
	protected $table = 'tasks';

	public function user()
    {
        return $this->belongsToMany('App\User','task_user','task_id','user_id')->withPivot('rating', 'comment','updated_at');
    }

    public function starredTask(){
        return $this->hasMany('App\StarredTask','task_id');
    }

    public function taskSignOffRequest(){
        return $this->hasMany('App\TaskSignOffRequest','task_id');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }

	public function taskComment()
    {
        return $this->hasMany('App\TaskComment');
    }

	public function taskNote()
    {
        return $this->hasMany('App\TaskNote');
    }
    
	public function taskAttachment()
    {
        return $this->hasMany('App\TaskAttachment');
    }

    public function subTask(){
        return $this->hasMany('App\SubTask');
    }

    public function taskCategory(){
        return $this->belongsTo('App\TaskCategory');
    }

    public function taskPriority(){
        return $this->belongsTo('App\TaskPriority');
    }
}
