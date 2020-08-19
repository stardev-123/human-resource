<?php
namespace App;
use Eloquent;

class UserContract extends Eloquent {

	protected $fillable = [
							'contract_type_id',
							'title',
							'description',
							'from_date',
							'to_date',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_contracts';

	public function user() {
    	return $this->belongsTo('App\User');
	}

	public function contractType() {
    	return $this->belongsTo('App\ContractType');
	}
}
