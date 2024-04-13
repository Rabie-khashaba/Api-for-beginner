<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    use ApiResponseTrait;


    public function index()
    {
        //$posts = Post::get();
        $posts = PostResource::collection(Post::get());
//        $msg = ["ok"];
//        return response($posts,200,$msg);

        return $this->apiResponse($posts, 'OK', 200);

    }


    public function show($id)
    {
        $posts = Post::find($id);
//        $posts = new PostResource(Post::find($id));

        if ($posts) {
            return $this->apiResponse(new PostResource(Post::find($id)), 'OK', 200);
        } else {
            return $this->apiResponse($posts, 'This Post Not Found', 401);
        }

    }


    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $posts = Post::create($request->all());

        if ($posts) {
            return $this->apiResponse(new PostResource($posts), 'post saved in DB', 201);
        } else {
            return $this->apiResponse(null, 'This Post Not Save', 400);
        }

    }


    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(),[
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $posts = Post::find($id);
        if (!$posts) {
            return $this->apiResponse(null, 'This Post Not Found', 401);
        }


        $posts->update($request->all());
        if ($posts) {
            return $this->apiResponse(new PostResource($posts), 'post Updated', 201);
        }

    }


    public function destroy( $id){

        $posts = Post::find($id);
        if (!$posts) {
            return $this->apiResponse(null, 'This Post Not Found', 401);
        }

        $posts->delete();
        return $this->apiResponse(new PostResource($posts), 'post deleted', 201);

    }

}
