<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pots  = Post::all();
        return view('Admin.Posts.tables')->with('data', $pots);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getContent(Request $request)
    {
        $postName = $request->input('postName');
        $result = OpenAI::completions()->create([
            'model'=>"text-davinci-003",
            "prompt"=>trim($postName),
            "temperature"=>1,
            "max_tokens"=>4000,
            "top_p"=>1,
            "frequency_penalty"=>0,
            "presence_penalty"=>0
        ]);

       $content = $result['choices'][0]['text'];
        return redirect('dashboard/post/create')->with('content',$content)->with('postName',$postName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {   

        $post = new Post();
        $post->title = $request['postName'];
        $post->content = $request['content'];
        $post->save();

        return redirect('dashboard/posts');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post)
    {
        return view('Admin.Posts.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $post = Post::findOrFail($id);
        if($post) $post->delete();
        return redirect('dashboard/posts');
    }
}
