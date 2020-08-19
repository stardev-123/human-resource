<?php
namespace App;
use Eloquent;

class Menu extends Eloquent {

	protected $fillable = [
							'name',
							'order',
							'visible',
						];
	protected $primaryKey = 'id';
	protected $table = 'menus';
	public $timestamps = false;
}
