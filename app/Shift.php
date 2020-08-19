<?php
namespace App;
use Eloquent;

class Shift extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'shifts';

	public function shiftDetail()
    {
        return $this->hasMany('App\ShiftDetail');
    }
}
