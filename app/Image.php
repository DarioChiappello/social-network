<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //indicar la tabla de este modelo
    protected $table = 'images';
    
    //relacion One To Many/ de uno a muchos
    public function comments(){
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
    
    //relacion One To Many/ de uno a muchos
    public function likes(){
        return $this->hasMany('App\Like');
    }
    
    //relacion Many To One/ de muchos a uno
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
