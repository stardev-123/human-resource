<?php
namespace App;
use Eloquent;

class DocumentType extends Eloquent {

	protected $fillable = [
							'name',
							'description'
						];
	protected $primaryKey = 'id';
	protected $table = 'document_types';
}
