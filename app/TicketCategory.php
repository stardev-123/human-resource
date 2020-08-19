<?php
namespace App;
use Eloquent;

class TicketCategory extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'ticket_categories';
}
