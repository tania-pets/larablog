<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use Validator;
use App\User;
use App\Tag;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request  $request)
    {
        $dateOrder = $request->input('date_order');
        $dateOrder = $dateOrder && in_array($dateOrder, ['asc', 'desc']) ? $dateOrder : 'desc';
        $posts = Post::status($request->input('status'))->orderBy('created_at', $dateOrder)->with('author')->with('tags')->get();
        return PostResource::collection($posts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data);
        if ($validator->fails()){
            $errors = $validator->errors();
            return $errors->toJson();
        }
        $userId = User::all()->random()->id;
        $data = array_merge($data, ['user_id' => $userId]);
        $post = Post::create($data);

        if ($request->get('tags')) {
            $post = $post->syncTags($request->get('tags'));
        }

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        $validator = $this->validator($data);
        if ($validator->fails()){
            $errors = $validator->errors();
            return $errors->toJson();
        }
        $post->update($data);

        if ($request->get('tags')) {
            $post = $post->syncTags($request->get('tags'));
        }
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
    }

    protected function validator($data) {
      return Validator::make($data, [
          'title' => 'required|min:3',
          'intro' => 'required|min:3',
          'content' => 'required|min:3',
          'category_id' => 'required|exists:post_categories,id',
          'status' => 'sometimes|required|in:0,1',
          'ordering' => 'sometimes|required|integer'
      ]);
    }
}
