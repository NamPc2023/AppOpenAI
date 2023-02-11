<?php

namespace App\Http\Controllers;

use App\Models\Script;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('Admin.Script.create');
    }

    public function getScriptContent(Request $request)
    {
        $postName = $request->input('postName');
        if(!empty($postName)){
       
            $result = OpenAI::completions()->create([
                'model'=>"text-davinci-003",
                "prompt"=>trim($postName),
                "temperature"=>1,
                "max_tokens"=>1000,
                "top_p"=>1,
                "frequency_penalty"=>0,
                "presence_penalty"=>0
            ]);

            $script = $result['choices'][0]['text'];
        }
 
        return redirect('dashboard/script')->with('script',$script)->with('postName',$postName);

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
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function show(Script $script)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function edit(Script $script)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Script $script)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Script  $script
     * @return \Illuminate\Http\Response
     */
    public function destroy(Script $script)
    {
        //
    }
}
