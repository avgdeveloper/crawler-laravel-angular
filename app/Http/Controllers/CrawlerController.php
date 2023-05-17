<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use MongoDB\Client;
use App\Http\Requests\SiteRequest;
use App\Services\CrawlService;
use Illuminate\Container\Container;


class CrawlerController extends Controller
{

    protected $crawlService;


    public function index()
    {
        return view('angular');
    }

   
    public function crawl(SiteRequest $request) 
    {

        if ($this->crawlService === null) {
            $this->crawlService = Container::getInstance()->make(CrawlService::class);
        }

        $url = $request->input("url");
        $update = $request->input("update");
        $depth = $request->input("depth");

        $result = $this->crawlService->crawl($url, $update, $depth);

        return response()->json($result);

    }

}
