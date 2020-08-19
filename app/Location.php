<?php
namespace App;
use Eloquent;

class Location extends Eloquent {

	protected $fillable = [
							'name',
                            'top_location_id',
                            'address_line_1',
                            'address_line_2',
                            'city',
                            'state',
                            'zipcode',
                            'country_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'locations';

    protected function children()
    {
        return $this->hasMany('App\Location','top_location_id','id');
    }

    protected function parent()
    {
        return $this->belongsTo('App\Location','top_location_id','id');
    }
}
