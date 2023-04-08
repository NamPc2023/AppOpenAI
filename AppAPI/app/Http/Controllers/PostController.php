<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Spatie\Async\Pool;
use Illuminate\Http\Request;

use App\Jobs\ProcessPost;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Web;


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

        $timezone = Carbon::now('Asia/Ho_Chi_Minh');

        $day = $timezone->day;
        if(strlen($day) == 1){
            $day ='0'.$day;
        }
        $month = $timezone->month;
        if(strlen($month) == 1){
            $month = '0'.$month;
        }
        $year = $timezone->year;
        $date = $year.'-'.$month.'-'.$day;

        $hour = $timezone->hour;
        $minute = $timezone->minute;

        $webs = Web::all();
        return view('Admin.Posts.create')->with('webs',$webs)->with('date',$date)->with('hour',$hour)->with('minute',$minute);
    }


    public function InProgress($data,$admin,$password,$loginUrl,$postNewUrl,$postSaveUrl,$postStatus)
    {
        ProcessPost::dispatch($this, $data,$admin,$password,$loginUrl,$postNewUrl,$postSaveUrl,$postStatus);
    }

    public function getPostContent(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'outline' => 'required',
                'web_id' => 'required',
                'postStatus' => 'required',
            ],
            [
                'outline' => 'Vui lòng nhập thông tin !',
                'web_id' => 'Vui lòng chọn trang web cần đăng !',
                'postStatus' => 'Vui lòng chọn trang thái cần đăng !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/post-create')
                ->withErrors($validator)
                ->withInput();
        }

        $result = $validator->safe()->only(['outline','web_id','postStatus']);

        $outline = $result['outline'];
        $web = Web::findOrFail($result['web_id']);
        $postStatus = $result['postStatus'];

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
            $this->InProgress($results,$web->admin,$web->password,$web->login_url,$web->post_new_url,$web->post_save_url,$postStatus);
        }
    
        return redirect('/dashboard/post-create')->with('msg','Tạo bài thành công');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function postSave($title,$content,$admin,$password,$loginUrl,$postNewUrl,$postSaveUrl,$postStatus = 1)
    {
        //login form action url
        $url = trim($loginUrl);
        $postinfo = [
            'log' => $admin,
            'pwd' => $password,
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
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
        curl_exec($ch);

        //page with the content I want to grab
        curl_setopt($ch, CURLOPT_URL, trim($postNewUrl));
        $html = curl_exec($ch);

        $encoding = mb_detect_encoding($html);
        $html = mb_convert_encoding($html, 'UTF-8', $encoding);

        $doc = new \DomDocument();
        @$doc->loadHtml($html);
        $xpdoc = new \DOMXpath($doc);

        $_wpnonce = $xpdoc->query('//*[@id="post"]/input[@id="_wpnonce"]/@value')[0]->nodeValue;
        $post_author = $xpdoc->query('//*[@id="post"]/input[@id="post_author"]/@value')[0]->nodeValue;
        $post_ID = $xpdoc->query('//*[@id="post"]/input[@id="post_ID"]/@value')[0]->nodeValue;

        $hidden_mm = $xpdoc->query('//*[@id="timestampdiv"]//*[@id="hidden_mm"]/@value')[0]->nodeValue;
        $hidden_jj = $xpdoc->query('//*[@id="timestampdiv"]//*[@id="hidden_jj"]/@value')[0]->nodeValue;
        $hidden_aa = $xpdoc->query('//*[@id="timestampdiv"]//*[@id="hidden_aa"]/@value')[0]->nodeValue;
        $hidden_hh = $xpdoc->query('//*[@id="timestampdiv"]//*[@id="hidden_hh"]/@value')[0]->nodeValue;
        $hidden_mn = $xpdoc->query('//*[@id="timestampdiv"]//*[@id="hidden_mn"]/@value')[0]->nodeValue;
      
        $newPost = Array();
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
            'post_title' => $title,
            'content' => $content,
        ];

        if($postStatus == 2){
            $newPost['publish'] = 'Đăng';
        }elseif($postStatus == 3){
            $newPost['publish'] = 'Dự kiến';

            $newPost['mm'] = '04';
            $newPost['jj'] = '20';
            $newPost['aa'] = '2023';
            $newPost['hh'] = '16';
            $newPost['mn'] = '00';

            $newPost['hidden_mm'] = $hidden_mm;
            $newPost['hidden_jj'] = $hidden_jj;
            $newPost['hidden_aa'] = $hidden_aa;
            $newPost['hidden_hh'] = $hidden_hh;
            $newPost['hidden_mn'] = $hidden_mn;

        }

        curl_setopt($ch, CURLOPT_URL, trim($postSaveUrl));
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
