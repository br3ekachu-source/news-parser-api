<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use SimpleXMLElement;

class ParserCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss"; 
        $response = Http::get($url);
        $items = new SimpleXMLElement($response);

        foreach ($items->channel->item as $item) {
            if (News::where('guid', $item->children('rbc_news', TRUE)->news_id)->doesntExist()) {
                News::create([
                    'guid' => $item->children('rbc_news', TRUE)->news_id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'publicateDate' => date("Y-m-d H:i:s", (int)$item->children('rbc_news', TRUE)->newsDate_timestamp),
                    'author' => $item->author,
                    'image' => $item->children('rbc_news', TRUE)->image->url,
                ]);
            }         
        }
        
        return Command::SUCCESS;
    }
}
