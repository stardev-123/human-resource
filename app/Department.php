<?php
namespace App;
use Eloquent;

class Department extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'departments';

	protected  function designation(){
        return $this->hasMany('App\Designation');
    }

}
