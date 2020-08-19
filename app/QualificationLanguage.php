<?php
namespace App;
use Eloquent;

class QualificationLanguage extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'qualification_languages';
}
