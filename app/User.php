<?php
/**
*     @SWG\Definition(
*         definition="author",
*         @SWG\Property(
*             property="id",
*             type="integer"
*         ),
*         @SWG\Property(
*             property="name",
*              type="string"
*         ),
*         @SWG\Property(
*             property="email",
*              type="string"
*         ),
*     )
*/
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];


    public function posts()
   {
       return $this->hasMany('App\Post');
   }
}
