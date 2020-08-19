<?php
namespace App;
use Eloquent;

class CustomField extends Eloquent {

	protected $fillable = [
							'form',
							'name',
							'title',
							'type',
							'options',
							'is_required'
						];
	protected $primaryKey = 'id';
	protected $table = 'custom_fields';

	public function customFieldValue()
    {
        return $this->hasMany('App\customFieldValue');
    }
}
