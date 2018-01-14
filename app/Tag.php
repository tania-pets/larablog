<?php
/**
*     @SWG\Definition(
*         definition="tag",
*         required={"tag"},
*         @SWG\Property(
*             property="id",
*             type="integer",
*             readOnly=true
*         ),
*         @SWG\Property(
*             property="tag",
*             type="string"
*         )
*     )
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
