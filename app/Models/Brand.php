<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;


class Brand extends Model
{
    use Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_active','photo'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];



    public function scopeActive($query){
        return $query -> where('is_active',1)->get();
    }

    public function getActive(){
        return  $this -> is_active  == 0 ?  __('admin/bands.notActive') : __('admin/bands.Active') ;
    }



    public function  getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/images/brands/' . $val) : "";
    }
}
