<?php
namespace App;
use Eloquent;

class Profile extends Eloquent
{

    protected $fillable = [
        'first_name', 
        'last_name',
        'date_of_birth',
        'date_of_anniversary',
        'work_phone',
        'work_phone_extension',
        'gender',
        'nationality',
        'marital_status',
        'unique_identification_number',
        'phone',
        'home_phone',
        'work_email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country_id',
        'zipcode',
        'facebook',
        'twitter',
        'google_plus',
        'github'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function designation()
    {
        return $this->belongsTo('App\Designation');
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
