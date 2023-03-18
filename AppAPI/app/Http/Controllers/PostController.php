<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Spatie\Async\Pool;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use Illuminate\Support\Facades\Validator;
use Orhanerday\OpenAi\OpenAi;


class PostController extends Controller
{
    public $content = '';

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


    public function Each($data)
    {

        $contents = [];
        foreach ($data as $k => $value) {
            // $value = substr($value,0,stripos($value,'</p>'));
            $value = trim(preg_replace('/\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($value))));
            if (!empty($value)) {
                $open_ai_key = env('OPENAI_API_KEY', false);
                $open_ai = new OpenAi($open_ai_key);

                $keyword = 'Viết một bài hoàn chỉnh theo từ khóa "' . trim($value) . '"';
                $response = $open_ai->chat([
                    'model' => 'gpt-3.5-turbo',
                    'messages' =>      [
                        [
                            "role" => "system",
                            "content" => $keyword,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 4000,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                ]);
                $result = json_decode($response, TRUE);
                $contents[] = trim(preg_replace('/\s+/', ' ', '<p>' . ucwords($value) . '</p><p>' . $result['choices'][0]['message']['content'])) . '</p>';
            }
        }
        return $contents;
    }

    public function getPostContent(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'outline' => 'required',
            ],
            [
                'outline' => 'Vui lòng nhập thông tin !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/post-create')
                ->withErrors($validator)
                ->withInput();
        }

        $outline = $validator->safe()->only(['outline'])['outline'];
        if (!empty($outline)) {
            // $data = array_filter(preg_split("/(\r\n|\n|\r)/", $outline));
            $data = array_filter(explode('<p>', $outline));
            $results = array();
            foreach ($data as $val) {
                $scripts = explode('<br>', $val);
                foreach ($scripts as $script) {
                    $results[] = $script;
                }
            }

            if (is_array($results)) {
                $pool = Pool::create();

                $pool->add(function () use ($results) {
                    return $this->Each($results);
                })->then(function ($output) {
                    $this->content = implode(' ', $output);
                })->catch(function (InvalidOrderException $e) {
                    echo $e->getMessage();
                });

                $pool->wait();
                return redirect('/dashboard/post-create')->with('content', $this->content)->with('outline', $outline);
            } else {
                return redirect('/dashboard/post-create');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function postSave(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'content' => 'required',
            ],
            [
                'title' => 'Vui lòng nhập thông tin !',
                'content' => 'Vui lòng nhập thông tin !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/post-create')
                ->withErrors($validator)
                ->withInput()->with('outline', $request['outline'])->with('content', $request['content']);
        }
        $postData = $validator->safe()->only(['title', 'content']);

        $post = new Post();
        $post->title = $postData['title'];
        $post->content = $postData['content'];
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
    public function edit(Request $request, $id)
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
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'content' => 'required',
            ],
            [
                'title' => 'Không để trống thông tin !',
                'content' => 'Không để trống thông tin !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/post-edit/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        $postData = $validator->safe()->only(['title', 'content']);

        $post = Post::findOrFail($id);
        $post->title = $postData['title'];
        $post->content = $postData['content'];
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
