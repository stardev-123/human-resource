<?php
namespace App;
use Eloquent;

class Library extends Eloquent {

	protected $fillable = [
							'title',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'librarys';

	public function user()
    {
        return $this->belongsToMany('App\User','library_user','library_id','user_id');
    }

    public function designation()
    {
        return $this->belongsToMany('App\Designation','library_designation','library_id','designation_id');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
