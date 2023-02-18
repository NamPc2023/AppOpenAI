<?php

namespace App\Http\Controllers;

use App\Models\Web;
use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $webs  = Web::all();
        return view('Admin.Webs.tables')->with('data', $webs);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIndex()
    {
        return view('Admin.Webs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        
        $name = $request->input('name');
        $url = $request->input('url');
        $admin = $request->input('admin');
        $password = $request->input('password');

        $web = new Web();
        $web->name = $name;
        $web->url = $url;
        $web->admin = $admin;
        $web->password = $password;
        $web->save();

        return redirect('/dashboard/web')->withErrors([
            'msg' => 'Create successful products',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Web  $web
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Web  $web
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $web = Web::findOrFail($id);
        return view('Admin.Webs.edit')->with('data', $web);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Web  $web
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $name = $request->input('name');
        $url = $request->input('url');
        $admin = $request->input('admin');
        $password = $request->input('password');

        $web = Web::findOrFail($id);
        $web->name = $name;
        $web->url = $url;
        $web->admin = $admin;
        $web->password = $password;
        $web->save();

        return redirect('/dashboard/web');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Web  $web
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $web = Web::findOrFail($id);
        if($web) $web->delete();
        return redirect('/dashboard/web');
    }
}
