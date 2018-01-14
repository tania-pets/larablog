<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Post extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id,
          'title' => $this->title,
          'intro' => $this->intro,
          'content' => $this->content,
          'category' => $this->category,
          'status' => $this->status,
          'author' => $this->author,
          'ordering' => $this->ordering,
          'slug' => $this->slug,
          'tags' => $this->tags,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
      ];


        //return parent::toArray($request);
    }
}
