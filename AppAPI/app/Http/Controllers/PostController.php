<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Spatie\Async\Pool;
use Illuminate\Http\Request;
use App\Exceptions\InvalidOrderException;
use App\Jobs\ProcessPost;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;


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
        ProcessPost::dispatch($data);
        echo "Tạo bài viết thàng công ";
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
                $this->Each($results);
                // return redirect('/dashboard/post-create')->with('content', $this->content)->with('outline', $outline);
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
        // $response = Http::asForm()->post('https://topgiaiphap.com/wp-login.php', [
        //     'log' => 'mentseotop@gmail.com',
        //     'pwd' => 'Ment_pbn@2022',
        //     'wp-submit' => 'Đăng nhập',
        //     'redirect_to' => 'https://topgiaiphap.com/wp-admin/',
        //     'testcookie' => '1',
        // ]);

        // dd($response->headers()['Set-Cookie']);


        $response = Http::withHeaders([
            'Cookie' => 'wordpress_sec_7b9c83940f471d359f59872252f6a225=ment_vn%7C1679903482%7CCtqxFtklGOrQLOEVUEZKh9sAFbiEJAzYXS8FMzsNWkz%7Cef3cf9c2b87030b9f00fc47dd6695644a931cc312845f29738e0e71848167798',
        ])->get('https://topgiaiphap.com/wp-admin/post-new.php');

        $html = $response->body();
        // echo $html;
        $encoding = mb_detect_encoding($html);
        //iso-8859-1
        //UTF-8
        $html = mb_convert_encoding($html, 'UTF-8', $encoding);
        // echo $html;
        $doc = new \DomDocument();
        @$doc->loadHtml($html);
        $xpdoc = new \DOMXpath($doc);

        $_wpnonce = $xpdoc->query('//*[@id="post"]/input[@id="_wpnonce"]/@value')[0]->nodeValue;
        $post_author = $xpdoc->query('//*[@id="post"]/input[@id="post_author"]/@value')[0]->nodeValue;
        $post_ID = $xpdoc->query('//*[@id="post"]/input[@id="post_ID"]/@value')[0]->nodeValue;
        echo $_wpnonce;
        echo '<br>';
        echo $post_author;
        echo '<br>';
        echo $post_ID;

        // $res = Http::withHeaders([
        //     'content-type: multipart/form-data; boundary=----WebKitFormBoundaryWlVEFesIpauNooAm',
        //     'Cookie' => 'wordpress_sec_7b9c83940f471d359f59872252f6a225=ment_vn%7C1679903482%7CCtqxFtklGOrQLOEVUEZKh9sAFbiEJAzYXS8FMzsNWkz%7Cef3cf9c2b87030b9f00fc47dd6695644a931cc312845f29738e0e71848167798',
        // ])->post('https://topgiaiphap.com/wp-admin/post.php', [
        //     '_wpnonce' => $_wpnonce,
        //     '_wp_http_referer' => '/wp-admin/post-new.php',
        //     'user_ID' => $post_author,
        //     'action' => 'editpost',
        //     'originalaction' => 'editpost',
        //     'post_author' => $post_author,
        //     'post_type' => 'post',
        //     'original_post_status' => 'auto-draft',
        //     'referredby' => '',
        //     'auto_draft' => '1',
        //     'post_ID' => $post_ID,
        //     'original_publish' => 'Đăng',
        //     'publish' => 'Đăng',
        //     'post_title' => 'test',
        //     'content' => '111111111',
        // ]);

        // dd('OK');
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
