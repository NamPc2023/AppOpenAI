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
        ProcessPost::dispatch($this, $data);
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
            }
        }
        return redirect('/dashboard/post-create')->with('msg','Tạo bài thành công');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function postSave($title,$content)
    {
        //login form action url
        $url = 'https://topgiaiphap.com/wp-login.php';
        $postinfo = [
            'log' => 'mentseotop@gmail.com',
            'pwd' => 'Ment_pbn@2022',
        ];
        $cookie_file_path = public_path().'/tmp/cookie.txt';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
        //set the cookie the site has for certain features, this is optional
        curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
        curl_setopt($ch,CURLOPT_USERAGENT,"user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, 'https://topgiaiphap.com/wp-login.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
        curl_exec($ch);

        //page with the content I want to grab
        curl_setopt($ch, CURLOPT_URL, "https://topgiaiphap.com/wp-admin/post-new.php");
        $html = curl_exec($ch);

        $encoding = mb_detect_encoding($html);
        $html = mb_convert_encoding($html, 'UTF-8', $encoding);

        $doc = new \DomDocument();
        @$doc->loadHtml($html);
        $xpdoc = new \DOMXpath($doc);

        $_wpnonce = $xpdoc->query('//*[@id="post"]/input[@id="_wpnonce"]/@value')[0]->nodeValue;
        $post_author = $xpdoc->query('//*[@id="post"]/input[@id="post_author"]/@value')[0]->nodeValue;
        $post_ID = $xpdoc->query('//*[@id="post"]/input[@id="post_ID"]/@value')[0]->nodeValue;
      
        $newPost = [
            '_wpnonce' => $_wpnonce,
            '_wp_http_referer' => '/wp-admin/post-new.php',
            'user_ID' => $post_author,
            'action' => 'editpost',
            'originalaction' => 'editpost',
            'post_author' => $post_author,
            'post_type' => 'post',
            'original_post_status' => 'auto-draft',
            'referredby' => '',
            'auto_draft' => '1',
            'post_ID' => $post_ID,
            'original_publish' => 'Đăng',
            'publish' => 'Đăng',
            'post_title' => $title,
            'content' => $content,
        ];

        curl_setopt($ch, CURLOPT_URL, "https://topgiaiphap.com/wp-admin/post.php");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $newPost);
        curl_exec($ch);

        curl_close($ch);

        

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
