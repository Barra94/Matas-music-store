<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'title','album_id','track_number','duration','file','original_file'
    ];

    protected $primaryKey = 'id';

    public function album()
    {
        return $this->belongsTo('App\Album');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }


}
