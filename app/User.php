<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function userContact()
    {
        return $this->hasMany('App\UserContact');
    }

    public function userBankAccount()
    {
        return $this->hasMany('App\UserBankAccount');
    }

    public function userDocument()
    {
        return $this->hasMany('App\UserDocument');
    }

    public function userQualification()
    {
        return $this->hasMany('App\UserQualification');
    }

    public function userExperience()
    {
        return $this->hasMany('App\UserExperience');
    }

    public function userDesignation()
    {
        return $this->hasMany('App\UserDesignation');
    }

    public function userLocation()
    {
        return $this->hasMany('App\UserLocation');
    }

    public function userContract()
    {
        return $this->hasMany('App\UserContract');
    }

    public function userEmployment()
    {
        return $this->hasMany('App\UserEmployment');
    }

    public function userShift()
    {
        return $this->hasMany('App\UserShift');
    }

    public function userLeave()
    {
        return $this->hasMany('App\UserLeave');
    }

    public function userSalary()
    {
        return $this->hasMany('App\UserSalary');
    }

    public function task()
    {
        return $this->belongsToMany('App\Task','task_user','user_id','task_id')->withPivot('rating', 'comment','updated_at');
    }

    public function library()
    {
        return $this->belongsToMany('App\Library','library_user','user_id','library_id');
    }

    public function announcement()
    {
        return $this->belongsToMany('App\Announcement','announcement_user','user_id','announcement_id');
    }

    public function routeNotificationForNexmo()
    {
        return $this->Profile->mobile;
    }

    public function getFullNameAttribute(){
        return $this->Profile->first_name.' '.$this->Profile->last_name;
    }

    public function getDesignationNameAttribute(){
        return ($this->Profile->designation_id) ? ($this->Profile->Designation->name) : '';
    }

    public function getDepartmentNameAttribute(){
        return ($this->Profile->designation_id) ? ($this->Profile->Designation->Department->name) : '';
    }

    public function getDesignationWithDepartmentAttribute(){
        return ($this->Profile->designation_id) ? ($this->Profile->Designation->name.' '.trans('messages.in').' '.$this->Profile->Designation->Department->name) : '';
    }

    public function getLocationNameAttribute(){
        return ($this->Profile->location_id) ? $this->Profile->Location->name : '';
    }

    public function getNameWithDesignationAndDepartmentAttribute(){
        return $this->Profile->first_name.' '.$this->Profile->last_name.(
            ($this->Profile->designation_id) ? (' ('.$this->Profile->Designation->name.' '.trans('messages.in').' '.$this->Profile->Designation->Department->name.')') : ''
            );
    }

    public function getNameWithDesignationAndLocationAttribute(){
        return $this->Profile->first_name.' '.$this->Profile->last_name.(
            ($this->Profile->designation_id) ? (' ('.$this->Profile->Designation->name.' '.trans('messages.in').' '.$this->Profile->Designation->Department->name.')') : ''
            ).(
            ($this->Profile->location_id) ? (' '.trans('messages.at').' '.$this->Profile->Location->name) : ''
            );
    }

    public function getNameWithLocationAttribute(){
        return $this->Profile->first_name.' '.$this->Profile->last_name.(
            ($this->Profile->location_id) ? (' '.trans('messages.at').' '.$this->Profile->Location->name) : ''
            );
    }

    public function getNameWithDesignationAttribute(){
        return $this->Profile->first_name.' '.$this->Profile->last_name.(($this->Profile->designation_id) ? ($this->Profile->Designation->Department->name) : '');
    }
}
