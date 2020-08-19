<?php
namespace App;
use Eloquent;

class Todo extends Eloquent {

	protected $fillable = [
							'user_id',
							'visibility',
							'title',
							'description',
							'date'
						];
	protected $primaryKey = 'id';
	protected $table = 'todos';

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
