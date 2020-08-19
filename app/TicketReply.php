<?php
namespace App;
use Eloquent;

class TicketReply extends Eloquent {

	protected $fillable = [
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'ticket_replies';

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
