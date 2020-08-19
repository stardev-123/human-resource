<?php
namespace App;
use Eloquent;

class Notification extends Eloquent {

	protected $fillable = [
							'user_id',
							'description',
							'url',
							'user',
							'user_read',
							'module',
							'module_id',
							'uuid'
						];
	protected $primaryKey = 'id';
	protected $table = 'notifications';

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
