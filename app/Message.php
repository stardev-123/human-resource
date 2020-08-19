<?php
namespace App;
use Eloquent;

class Message extends Eloquent {

	protected $fillable = [
							'from_user_id',
							'to_user_id',
							'subject',
							'body',
							'is_read',
							'reply_id',
							'delete_sender',
							'delete_receiver',
						];
	protected $primaryKey = 'id';
	protected $table = 'messages';

    public function userTo()
    {
        return $this->belongsTo('App\User','to_user_id'); 
    }

    public function userFrom()
    {
        return $this->belongsTo('App\User','from_user_id'); 
    }

    public function replies(){
    	return $this->hasMany('App\Message','reply_id');
    }

    public function reply()
    {
        return $this->belongsTo('App\Message','reply_id'); 
    }
}
