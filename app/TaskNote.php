<?php
namespace App;
use Eloquent;

class TaskNote extends Eloquent {

    protected $fillable = [
                            'note',
                            'user_id',
                            'task_id'
                        ];
    protected $primaryKey = 'id';
    protected $table = 'task_notes';

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
