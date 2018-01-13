<?php
/**
 * Tag Model definition
 *
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'tag'
    ];

    protected $hidden = ['pivot'];

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }


}
