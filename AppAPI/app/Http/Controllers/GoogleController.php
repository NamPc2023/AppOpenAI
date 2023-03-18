<?php

namespace App\Http\Controllers;

use App\Models\Google;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search()
    {
        $keyword = 'giải rubik 4x4';
        $response = Http::get('https://www.google.com/search?q='.urlencode($keyword));
        $html = $response->body();

        $encoding = mb_detect_encoding($html);
        $html = mb_convert_encoding($html, 'UTF-8', $encoding);

        $doc = new \DomDocument();
        @$doc->preserveWhiteSpace = false;
        @$doc->loadHtml($html);
        $xpath = new \DOMXpath($doc); 
        $nodes = $xpath->query("//h3/parent::div/parent::div/parent::a/@href");
        $firstUrl = '';
        foreach($nodes as $node){
            $link = $node->nodeValue;
            $link = explode('q=',$link)[1];
            $url = explode('&',$link)[0];
            if(stripos($url,'youtube.com') === false){
                $firstUrl= $url;
                break;
            }
        }

        // echo $firstUrl;

        $res = Http::get($firstUrl);
        $html2 = $res->body();

        $encoding2 = mb_detect_encoding($html2);
        $html2 = mb_convert_encoding($html2, 'UTF-8', $encoding2);

        $doc2 = new \DomDocument();
        @$doc2->preserveWhiteSpace = false;
        @$doc2->loadHtml($html2);
        $xp = new \DOMXpath($doc2); 
        $results = $xp->query("//text()");

        foreach($results as $result){
            echo $result->nodeValue;
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Google $google)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Google $google)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Google $google)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Google $google)
    {
        //
    }
}
