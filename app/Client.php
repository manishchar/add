<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'fname','lname','email','mobile','logo','password','address','createdby','IsActive','IsActiveFront','company_name','totalslots','avilslots'
    ];
}
