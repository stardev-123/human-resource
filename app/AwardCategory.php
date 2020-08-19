<?php
namespace App;
use Eloquent;

class AwardCategory extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'award_categories';
}
