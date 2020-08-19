<?php
namespace App;
use Eloquent;

class TicketPriority extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'ticket_priorities';
}
