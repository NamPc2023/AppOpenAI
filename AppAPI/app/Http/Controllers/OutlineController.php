<?php

namespace App\Http\Controllers;

use App\Models\Outline;
use App\Models\Post;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;



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
                "max_tokens"=>3000,
                "top_p"=>1,
                "frequency_penalty"=>0,
                "presence_penalty"=>0
            ]);

            $outline = $result['choices'][0]['text'];
        }
 
        return redirect('dashboard/outline')->with('outline',$outline)->with('postName',$postName);

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
