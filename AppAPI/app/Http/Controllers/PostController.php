<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use VXM\Async\AsyncFacade as Async;

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


    public function create(Request $request)
    {
        return view('Admin.Posts.create');

    }


    public function Each($data){

        $contents = [];
        foreach($data as $k => $value){

            $result = OpenAI::completions()->create([
                'model'=>"text-davinci-003",
                "prompt"=>trim($value),
                "temperature"=>1,
                "max_tokens"=>4000,
                "top_p"=>1,
                "frequency_penalty"=>0,
                "presence_penalty"=>0
            ]);
            $contents[] = strtoupper($value)." :".$result['choices'][0]['text'].'<br/>';
        }
        return $contents;
    }

    public function getPostContent(Request $request)
    {
        $outline = $request['outline'];

        if(!empty($outline)){

            $data = array_filter(preg_split("/(\r\n|\n|\r)/", $outline));

            Async::run(function () use ($data) {
               return $this->Each($data);
            });
            
            $content = implode(' ',Async::wait()[0]);
            // echo '<prev>',var_dump(Async::wait()),'</prev>';
        }
    
        return redirect('/dashboard/post-create')->with('content',$content)->with('outline',$outline);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function postSave(Request $request)
    {
        $title = $request['title'];
        $postContent = $request['content'];

        $post = new Post();
        $post->title = $title;
        $post->content = $postContent;
        $post->save();

        return redirect('/dashboard/post-list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $post = Post::findOrFail($id);
        return view('Admin.Posts.edit')->with('post', $post);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->title = $request['title'];
        $post->content = $request['content'];
        $post->save();
        return redirect('/dashboard/post-list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post) $post->delete();
        return redirect('/dashboard/post-list');
    }
}
