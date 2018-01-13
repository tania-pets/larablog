<?php
/**
 * Post Category Model definition
 *
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'status', 'ordering',
    ];
}
