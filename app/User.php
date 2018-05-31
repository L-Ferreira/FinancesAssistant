<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'profile_photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFormattedTypeAttribute()
    {
        switch ($this->type) {
            case '0':
                return 'Administrator';
            case '1':
                return 'NormalUser';
        }

        return 'Unknown';
    }

    public function isAdmin()
    {
        return $this->type == '0';
    }

    public function isNormalUser()
    {
        return $this->type == '1';
    }
    public function isAssociateOf($associateUser)
    {
        $associateMembers = AssociateMembers::all();
        foreach ($associateMembers as $associatedMember) {
            if ($associatedMember->associated_user_id == Auth::user()->id && $associateUser == $associatedMember->main_user_id) {
                return true;
            }
        }
        return false;
    }

}
