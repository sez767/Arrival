<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Take;

class Arrival extends Model
{
    protected $table = 'arrival';

    protected $fillable = [
        'name',
        'description',
        'begin',
        'end',
        'tour_name',
    
    ];
    
    
    protected $dates = [
        'begin',
        'end',
    
    ];
    public $timestamps = false;
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/arrivals/'.$this->getKey());
    }
    public function takes()
    {
        return $this->hasMany('App\Models\Take');
    }
    public function tour(){

        return $this->belongsTo('App\Models\Tour');

    }
    public function images()
    {
        return $this->belongsToMany('App\Models\Image');
        
    }
}
