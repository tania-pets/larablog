<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{
    use Sluggable;

    public $timestamps = false;

    protected $fillable = [
        'tag', 'slug'
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }



    /**
    * Return the sluggable configuration array for this model.
    * @return array
    */
    public function sluggable()
    {
      return [
          'slug' => [
              'source' => 'tag'
          ]
      ];
    }



}
