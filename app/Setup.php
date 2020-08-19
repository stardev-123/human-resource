<?php
namespace App;
use Eloquent;

class Setup extends Eloquent {

	protected $fillable = [
							'module',
							'completed'
						];
	protected $table = 'setup';
	public $timestamps = false;
}
