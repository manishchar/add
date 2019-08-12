<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Schedule;
class Advertise extends Model
{
    protected $fillable = [
        'client_id','location_id','advertise_name','createdby','IsActive',
    ];

    public function client(){
        return $this->belongsTo('App\Client', 'client_id', 'id');
    }
    public function location(){
        return $this->belongsTo('App\Location', 'location_id', 'id');
    }
    public static function videoLenght($id,$from,$to){
    	//return $id;
		return Schedule::where('advertise_id', $id)->where('fromDate','>=',$from)->where('toDate','<=',$to)->sum("videoTotalLength");
        //return Schedule::all();
    }
    public static function videoLenghtEdit($advertise_id,$schedule_id){
        //return $id;
        return Schedule::where('IsActive', '1')->where('location_id', $advertise_id)->where('id','!=', $schedule_id)->sum("videoTotalLength");
        //return Schedule::all();
    }
     public static function videoLenghtForAdd($id){
        return Schedule::where('IsActive', '1')->where('location_id', $id)->sum("videoTotalLength");
    }

     public static function videoLenghtForAdd1($id){
        return Schedule::where('location_id', $id)->sum("videoTotalLength");
    }

    public function allSchedule()
    {
        return $this->hasMany('App\Schedule');
    }
}
