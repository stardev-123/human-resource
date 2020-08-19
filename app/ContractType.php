<?php
namespace App;
use Eloquent;

class ContractType extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'contract_types';
}
