<?php
namespace App;
use Eloquent;

class UserShift extends Eloquent {

	protected $fillable = [
							'user_id',
							'shift_id',
							'from_date',
							'to_date',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_shifts';

	public function user()
    {
        return $this->belongsTo('App\User');
    }

	public function shift()
    {
        return $this->belongsTo('App\Shift');
    }
}
