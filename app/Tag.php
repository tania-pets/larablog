<?php
/**
*     @SWG\Definition(
*         definition="tag",
*         @SWG\Property(
*             property="id",
*             type="integer"
*         ),
*         @SWG\Property(
*             property="tag",
*             type="string"
*         )
*     )
*     @SWG\Definition(
*         definition="addtag",
*         required={"title"},
*         @SWG\Property(
*             property="tag",
*             type="string",
*             example="weather"
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

    /**
     * Get post's of with the tags
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }


}
