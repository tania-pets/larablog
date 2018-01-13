<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

    protected $fillable = [
        'title', 'intro', 'content', 'category_id', 'user_id', 'status', 'ordering', 'slug'
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }


    /**
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable()
    {
      return [
          'slug' => [
              'source' => 'title'
          ]
      ];
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
   * Scope to fetch per post status
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeStatus($query, $status)
  {
      if (!is_null($status) && in_array($status, [0,1])) {
          return $query->where('status', $status);
      }
  }


  public function syncTags($tags) {
      $tags = explode(',', $tags);
      $tagIds = [];
      foreach ($tags as $tag) {
          $dbTag = Tag::firstOrCreate(['tag'=>$tag]);
          $tagIds[] = $dbTag->id;
      }
      $this->tags()->sync($tagIds);
      return $this;
  }


}
