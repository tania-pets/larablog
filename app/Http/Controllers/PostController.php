<?php
/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="Post Controller API",
 *     version="1.0.0"
 *   )
 * )
 */


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
     * @SWG\Get(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="List posts",
     *     @SWG\Parameter(
     *         description="Post status",
     *         in="query",
     *         name="status",
     *         type="integer",
     *         enum={0,1}
     *     ),
     *     @SWG\Parameter(
     *         description="Direction of created date sorting",
     *         in="query",
     *         name="date_order",
     *         type="string",
     *         default="desc",
     *         enum={"asc","desc"}
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="List of posts",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/post")
     *         )
     *      )
     * ),
     */
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request  $request)
    {
        $posts = Post::status($request->input('status'))->tags($request->input('tags'))->sortDate($request->input('date_order'))->with('author')->get();
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
          'intro' => 'sometimes|required|min:3',
          'content' => 'required|min:3',
          'category_id' => 'required|exists:post_categories,id',
          'status' => 'sometimes|required|in:0,1',
          'ordering' => 'sometimes|required|integer'
      ]);
    }
}
