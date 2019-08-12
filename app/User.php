<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type','CreatedBy','name', 'email', 'password','Phone','Designation','RoleID','UpdatedBy','IsVerify'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    public function org(){
        return $this->belongsTo('App\Organizationmaster', 'OrgID', 'id');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password)
    {   
        $this->attributes['password'] = bcrypt($password);
    }


      public function permission()
    {   
        return "manish";
        //$this->attributes['password'] = bcrypt($password);
    }
}
