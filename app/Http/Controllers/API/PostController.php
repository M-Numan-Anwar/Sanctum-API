<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();

        return response()->json([
            'status' => true,
            'message' => 'All Post Data.',
            'data' => $data,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
            );

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()->all()
                ],401);
            }

            $image => $request->image;
            $ext => $image->getClientOriginalExtention();
            $imageName => time(). '.' . $ext;
            $imge->move(public_path(). '/uploads', $imageName);

            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->imageName,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post Created Successfully',
                 'post' => $post,
            ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id' => $id])->get();

        return response()->json([
            'status' => true,
            'message' => 'Your Single Post.',
             'data' => $data,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
            );

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()->all()
                ],401);
            }

            $post = Post::select('id', 'image')->get();

            if($request->image != ''){
                $path = public_path(). '/uploads';

                if($post->image != '' && $post->image != null){
                    $old_file = $path. $post->image;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }
                }

                $image => $request->image;
                $ext => $image->getClientOriginalExtention();
                $imageName => time(). '.' . $ext;
                $imge->move(public_path(). '/uploads', $imageName);
    
            }else{
                $imageName-> $post->image;
            }

          
            $post = Post::where(['id' => $id])->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->imageName,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post updated Successfully',
                 'post' => $post,
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
