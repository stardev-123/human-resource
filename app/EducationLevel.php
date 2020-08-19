<?php
namespace App;
use Eloquent;

class EducationLevel extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'education_levels';
}
