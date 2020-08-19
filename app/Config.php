<?php
namespace App;
use Eloquent;

class Config extends Eloquent {

	protected $fillable = [
							'name',
							'value',
						];
	protected $primaryKey = 'id';
	protected $table = 'config';
	public $timestamps = false;
}
