<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    //
    protected $table="car_images";
    public $fillable = ['name','path','type','size','car_id','agency_id'];
}
