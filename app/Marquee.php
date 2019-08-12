<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marquee extends Model
{
    protected $fillable = [
        'id','content','created_at','updated_at',
    ];

    public function city(){
        return $this->belongsTo('App\City', 'city_id', 'city_id');
    }

    public function screenType(){
        return $this->belongsTo('App\Screen_type', 'screen_type', 'id');
    }

    public static function screenSize1(){
        return $this->hasMany('App\Comment', 'foreign_key');
        //     //return $id;
        //     return Schedule::where('advertise_id', $id)->where('fromDate','>=',$from)->where('toDate','<=',$to)->sum("videoTotalLength");
        //     //return Schedule::all();
     }
    public function allAdvertise()
    {
        return $this->hasMany('App\Advertise');
    }

    public function screenSize(){
        return $this->belongsTo('App\Screen_size', 'screen_size', 'id');
    }

}
