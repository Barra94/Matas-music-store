<?php
namespace App;


use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title', 'artist_id', 'artwork','genre'
    ];

    protected $primaryKey = 'id';

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function songs()
    {
        return $this->hasMany('App\Song');
    }

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }

    public function h()
    {
        return 'hh';
    }
}
