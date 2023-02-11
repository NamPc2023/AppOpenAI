<?php

namespace App\Http\Controllers;

use App\Models\Outline;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use VXM\Async\AsyncFacade as Async;


class OutlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.Outline.create');
    }

    public function getOutlineContent(Request $request)
    {
        $postName = $request->input('postName');
        if(!empty($postName)){
            $keyword = 'Tạo outline với chủ đề "'.$postName.'" bằng tiếng việt';

            $result = OpenAI::completions()->create([
                'model'=>"text-davinci-003",
                "prompt"=>trim($keyword),
                "temperature"=>1,
                "max_tokens"=>4000,
                "top_p"=>1,
                "frequency_penalty"=>0,
                "presence_penalty"=>0
            ]);

            $outline = $result['choices'][0]['text'];
        }
 
        return redirect('dashboard/outline')->with('outline',$outline)->with('postName',$postName);

    }

    public function convertIndex(Request $request)
    {
        return view('Admin.Outline.convert');

    }


    public function Each($data){

        $contents = [];
        foreach($data as $k => $value){
            $str = substr($value,stripos($value,'.')+1);

            $result = OpenAI::completions()->create([
                'model'=>"text-davinci-003",
                "prompt"=>"Viết bài ".trim($str),
                "temperature"=>1,
                "max_tokens"=>4000,
                "top_p"=>1,
                "frequency_penalty"=>0,
                "presence_penalty"=>0
            ]);
            $contents[$str] = $result['choices'][0]['text'];
        }
        return $contents;
    }

    public function convert(Request $request)
    {
        $outline = $request->input('outline');

        if(!empty($outline)){

            $data = array_filter(preg_split("/(\r\n|\n|\r)/", $outline));

            Async::run(function () use ($data) {
               return $this->Each($data);
            });
            
            echo '<pre>' , var_dump(Async::wait()) , '</pre>';
        }
        // echo '<pre>' , var_dump($contents) , '</pre>';
        // return redirect('dashboard/convert')->with('post',$post)->with('outline',$outline);

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function show(Outline $outline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function edit(Outline $outline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outline $outline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outline $outline)
    {
        //
    }
}
