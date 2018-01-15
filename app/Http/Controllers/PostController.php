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
     *     @SWG\Parameter(
     *         description="Tags, comma separated if many (e.g media,news)",
     *         in="query",
     *         name="tags",
     *         type="string"
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
     * @SWG\Post(
     *     path="/posts",
     *     operationId="addPost",
     *     tags={"Posts"},
     *     description="Adds a new end post in database",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/addpost")
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Returns created post object",
     *         @SWG\Schema(ref="#/definitions/post")
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Unprocessable Entity - Validation errors",
     *         @SWG\Schema(ref="#/definitions/Error")
     *     )
     * )
     * )
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = $this->validator($data, 'post');
        if ($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                'message' => $errors
            ], 422);
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
    * @SWG\Get(
    *     path="/posts/{id}",
    *     @SWG\Parameter(
    *         description="ID of post to get",
    *         in="path",
    *         name="id",
    *         required=true,
    *         type="integer"
    *     ),
    *     operationId="getpost",
    *     tags={"Posts"},
    *     description="Fetches a post",
    *     produces={"application/json"},
    *     @SWG\Response(
    *         response=200,
    *         description="Post response",
    *         @SWG\Schema(ref="#/definitions/post")
    *     ),
    *     @SWG\Response(
    *         response="404",
    *         description="Not found",
    *     )
    *     )
    */
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
      *@SWG\Put(
      *     path="/posts/{id}",
      *     @SWG\Parameter(
      *         description="ID of post to update",
      *         in="path",
      *         name="id",
      *         required=true,
      *         type="integer"
      *     ),
      *     operationId="editpost",
      *     tags={"Posts"},
      *     description="Edits a post",
      *     produces={"application/json"},
      *     @SWG\Parameter(
      *         name="body",
      *         in="body",
      *         required=true,
      *         @SWG\Schema(ref="#/definitions/editpost")
      *     ),
      *     @SWG\Response(
      *         response=200,
      *         description="Updated Post response",
      *         @SWG\Schema(ref="#/definitions/post")
      *     ),
      *     @SWG\Response(
      *         response="422",
      *         description="Validation errors",
      *         @SWG\Schema(ref="#/definitions/Error")
      *     )
      * )
      */
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
        $validator = $this->validator($data, 'edit');
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
     *
     * 	@SWG\Delete(
     * 		path="/posts/{id}",
     * 		tags={"Posts"},
     * 		operationId="deletePost",
     * 		summary="Remove post entry",
     * 		@SWG\Parameter(
     * 			name="id",
     * 			in="path",
     * 			required=true,
     * 			type="integer",
     * 			description="Id of the post to delete",
     * 		),
     * 		@SWG\Response(
     * 			response=200,
     * 			description="success",
     * 		),
     * 		@SWG\Response(
     * 			response="404",
     * 			description="Not found"
     * 		),
     * 	)
     *
     */
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

    protected function validator($data, $action) {
      $required = $action == 'post' ? 'required' : 'sometimes';
      return Validator::make($data, [
          'title' => $required . '|min:3',
          'intro' => 'sometimes|required|min:3',
          'content' => $required . '|min:3',
          'category_id' => $required . '|exists:post_categories,id',
          'status' => 'sometimes|required|in:0,1',
          'ordering' => 'sometimes|required|integer'
      ]);
    }


}
