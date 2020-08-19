<?php
namespace App;
use Eloquent;

class Backup extends Eloquent {

	protected $fillable = [
							'file'
						];
	protected $primaryKey = 'id';
	protected $table = 'backups';
}
