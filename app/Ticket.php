<?php
namespace App;
use Eloquent;

class Ticket extends Eloquent {

	protected $fillable = [
							'subject',
							'ticket_category_id',
							'ticket_priority_id',
						];
	protected $primaryKey = 'id';
	protected $table = 'tickets';

    public function ticketCategory()
    {
        return $this->belongsTo('App\TicketCategory');
    }

    public function ticketPriority()
    {
        return $this->belongsTo('App\TicketPriority');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function ticketReply()
    {
        return $this->hasMany('App\TicketReply','ticket_id');
    }
}
