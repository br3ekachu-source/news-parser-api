<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\News;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use XMLReader;

class NewsController extends Controller
{
    public function index() {
        $url = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss"; 
        $response = Http::get($url);
        $items = new SimpleXMLElement($response);
        foreach ($items->channel->item as $item) {
            if (News::where('guid', $item->children('rbc_news', TRUE)->news_id)->doesntExist()) {
                $pictureUrl = $item->children('rbc_news', TRUE)->image->url;
                $pictureName = basename($pictureUrl);
                News::create([
                    'guid' => $item->children('rbc_news', TRUE)->news_id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'publicateDate' => date("Y-m-d H:i:s", (int)$item->children('rbc_news', TRUE)->newsDate_timestamp),
                    'author' => $item->author,
                    'image' => $pictureName,
                ]);
                if ($pictureUrl != null) {
                    $picture = Http::get($pictureUrl);
                    Storage::disk('images')->put($pictureName, $picture);
                }
            }         
        }
    }
}
