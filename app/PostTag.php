<?php
/**
 * Post - Tag pivot Model definitions (used in seeder)
 *
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    public $timestamps = false;
    protected $table = 'post_tag';


}
