<?php
namespace App;
use Eloquent;

class QualificationSkill extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'qualification_skills';
}
