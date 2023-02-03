<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;
use XMLReader;

class NewsController extends Controller
{
    public function index() {
        $url = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss"; 
        $response = Http::get($url);
        $items = new SimpleXMLElement($response);
        return var_dump($items);
    }
}
