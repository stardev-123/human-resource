<?php
namespace App;
use Eloquent;

class UserBankAccount extends Eloquent {

	protected $fillable = [
							'is_primary',
							'bank_name',
							'account_name',
							'account_number',
							'bank_code',
							'bank_branch'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_bank_accounts';

	public function user() {
    	return $this->belongsTo('App\User');
	}
}
