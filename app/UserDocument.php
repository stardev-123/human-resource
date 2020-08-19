<?php
namespace App;
use Eloquent;

class UserDocument extends Eloquent {

	protected $fillable = [
							'document_type_id',
							'date_of_expiry',
							'title',
							'description',
							'user_id'
						];
	protected $primaryKey = 'id';
	protected $table = 'user_documents';

	public function user() {
    	return $this->belongsTo('App\User');
	}

	public function documentType() {
    	return $this->belongsTo('App\DocumentType');
	}
}
