<?php
namespace App;
use Eloquent;

class ShiftDetail extends Eloquent {

	protected $fillable = [
							'shift_id',
							'day',
							'in_time',
							'out_time',
							'overnight'
						];
	protected $primaryKey = 'id';
	protected $table = 'shift_details';

	public function shift()
    {
        return $this->belongsTo('App\Shift');
    }
}
