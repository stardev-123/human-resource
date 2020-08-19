<?php
namespace App;
use Eloquent;

class Award extends Eloquent {

	protected $fillable = [
							'award_category_id',
							'description',
							'date_of_award',
							'duration'
						];
	protected $primaryKey = 'id';
	protected $table = 'awards';

	public function user()
    {
        return $this->belongsToMany('App\User','award_user','award_id','user_id');
    }

	public function awardCategory()
    {
        return $this->belongsTo('App\AwardCategory','award_category_id');
    }

	public function userAdded()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
