<?php
namespace App;
use Eloquent;

class Chat extends Eloquent {

	protected $fillable = [
							'message'
						];
	protected $primaryKey = 'id';
	protected $table = 'chat';

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
