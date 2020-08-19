<?php
namespace App;
use Eloquent;

class CustomFieldValue extends Eloquent {

	protected $fillable = [
							'unique_id',
							'custom_field_id',
							'value'
						];
	protected $primaryKey = 'id';
	protected $table = 'custom_field_values';

	public function customField()
    {
        return $this->belongsTo('App\customField');
    }
}
