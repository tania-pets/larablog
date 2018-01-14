<?php
/**
*     @SWG\Definition(
*         definition="post",
*         required={"title", "content", "category_id"},
*         @SWG\Property(
*             property="id",
*             type="integer",
*             readOnly=true
*         ),
*         @SWG\Property(
*             property="title",
*             type="string"
*         ),
*         @SWG\Property(
*             property="intro",
*             type="string"
*         ),
*         @SWG\Property(
*             property="content",
*             type="string"
*         ),
*         @SWG\Property(
*             property="category_id",
*             type="integer"
*         ),
*         @SWG\Property(
*             property="user_id",
*             type="integer",
*             readOnly=true
*         ),
*         @SWG\Property(
*             property="status",
*             type="integer",
*         ),
*         @SWG\Property(
*             property="ordering",
*             type="integer",
*         ),
*         @SWG\Property(
*             property="slug",
*             type="string",
*             readOnly=true
*         ),
*         @SWG\Property(
*             property="tags",
*             type="array",
*             @SWG\Items(ref="#/definitions/tag")
*         ),
*     )
*/
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

    /**
     * Get post's author
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get post's category
     */
    public function category()
    {
        return $this->belongsTo('App\PostCategory', 'category_id');
    }

    /**
    * Scope to fetch per post status
    * @param \Illuminate\Database\Eloquent\Builder $query
    * @param int $status, 0|1
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeStatus($query, $status)
    {
      if (!is_null($status) && in_array($status, [0,1])) {
          return $query->where('status', $status);
      }
    }


      /**
     * Scope to fetch per tags
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tags separeted by comma if many
     * @return \Illuminate\Database\Eloquent\Builder
     */
     public function scopeTags($query, $tags)
    {
        if (!is_null($tags)) {
            $tags = explode(',', $tags);
            return $query->whereHas('tags', function ($query) use($tags) {
                $query->whereIn('tag', $tags);
            });
        }
    }

    /**
     * Scope to sort created at by direction
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string direction asc|desc
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortDate($query, $sortDir) {
        $sortDir = $sortDir && in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';
        return $query->orderBy('created_at', $sortDir);
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


  /**
   * Use this to use slug for post urls
   * Get the route key for the model.
   * @return string
   */
  // public function getRouteKeyName()
  // {
  //     return 'slug';
  // }



}
