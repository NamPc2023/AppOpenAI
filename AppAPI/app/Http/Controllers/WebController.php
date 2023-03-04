<?php

namespace App\Http\Controllers;

use App\Models\Web;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'url' => 'required',
                'admin' => 'required',
                'password' => 'required',
            ],
            [
                'name' => 'Vui lòng điền thông tin !',
                'url' => 'Vui lòng điền thông tin !',
                'admin' => 'Vui lòng điền thông tin !',
                'password' => 'Vui lòng điền thông tin !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/web-create')
                ->withErrors($validator)
                ->withInput();
        }
        $data = $validator->safe()->only(['name','url','admin','password']);
        
        $web = new Web();
        $web->name = $data['name'];
        $web->url = $data['url'];
        $web->admin = $data['admin'];
        $web->password = $data['password'];
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'url' => 'required',
                'admin' => 'required',
                'password' => 'required',
            ],
            [
                'name' => 'Vui lòng điền thông tin !',
                'url' => 'Vui lòng điền thông tin !',
                'admin' => 'Vui lòng điền thông tin !',
                'password' => 'Vui lòng điền thông tin !',
            ]
        );

        if ($validator->fails()) {
            return redirect('/dashboard/web-edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }
        $data = $validator->safe()->only(['name','url','admin','password']);

        $web = Web::findOrFail($id);
        $web->name = $data['name'];
        $web->url = $data['url'];
        $web->admin = $data['admin'];
        $web->password = $data['password'];
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
