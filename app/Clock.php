<?php
namespace App;
use Eloquent;

class Clock extends Eloquent {

	protected $fillable = [
							'user_id',
							'date',
							'clock_in',
							'clock_out'
						];
	protected $primaryKey = 'id';
	protected $table = 'clocks';

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
