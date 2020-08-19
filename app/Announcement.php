<?php
namespace App;
use Eloquent;

class Announcement extends Eloquent {

	protected $fillable = [
							'title',
							'description',
							'from_date',
							'to_date',
							'audience'
						];
	protected $primaryKey = 'id';
	protected $table = 'announcements';

	public function user()
    {
        return $this->belongsToMany('App\User','announcement_user','announcement_id','user_id');
    }

    public function designation()
    {
        return $this->belongsToMany('App\Designation','announcement_designation','announcement_id','designation_id');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
