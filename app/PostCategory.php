<?php
/**
*     @SWG\Definition(
*         definition="category",
*         @SWG\Property(
*             property="id",
*             type="integer"
*         ),
*         @SWG\Property(
*             property="name",
*             type="string"
*         ),
*         @SWG\Property(
*             property="status",
*             type="integer"
*         ),
*         @SWG\Property(
*             property="ordering",
*             type="integer",
*         )
*     )
*/
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'status', 'ordering',
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
