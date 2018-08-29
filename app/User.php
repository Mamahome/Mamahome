<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
    public function requirement(){
        return $this->hasOne('App\Requirement','id','generated_by');
    }
     function tlward(){
        return $this->hasOne(Tlwards::class,'user_id','id');
    }
    function activity(){
        return $this->hasMany(ActivityLog::class,'updater','id');
    }
    function activity1(){
        return $this->hasMany(ActivityLog::class,'enquiry','id');
    }
}
