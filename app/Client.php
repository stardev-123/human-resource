<?php
namespace App;
use Eloquent;

class Client extends Eloquent {

	protected $fillable = [
							'email',
							'first_name',
							'last_name',
							'date_of_birth',
							'gender',
							'phone',
							'address_line_1',
							'address_line_2',
							'city',
							'state',
							'zipcode',
							'country_id',
							'note'
						];
	protected $primaryKey = 'id';
	protected $table = 'clients';

	public function user()
    {
        return $this->belongsToMany('App\User','id');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
