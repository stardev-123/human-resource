<?php
namespace App;
use Eloquent;

class BulkUpload extends Eloquent {

	protected $fillable = [
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'bulk_uploads';

	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
