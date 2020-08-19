<?php
namespace App;
use Eloquent;

class IpFilter extends Eloquent {

	protected $fillable = [
							'start',
							'end'
						];
	protected $primaryKey = 'id';
	protected $table = 'ip_filters';
}
