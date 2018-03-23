<?php

namespace App\Console\Commands;

use App\Models\MalItem;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Jikan\Jikan;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class FetchMalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mal:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update MyAnimeList data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = microtime(true);

        $jikan = new Jikan();

        $malItems = MalItem::all();

        $this->line('[' . Carbon::now() . ']');
        $this->line('fetching data from MyAnimeList.net...');

        foreach ($malItems as $malItem) {
            $this->line('  #' . $malItem->mal_id);

            $data = $jikan->Manga($malItem->mal_id)->response;

            $malItem->link_canonical = $data['link_canonical'];
            $malItem->title = $data['title'];
            $malItem->title_english = $data['title_english'];
            $malItem->title_japanese = $data['title_japanese'];
            $malItem->title_synonyms = $data['title_synonyms'];
            $malItem->status = $data['status'];
            $malItem->image_url = $data['image_url'];
            $malItem->volumes = $data['volumes'];
            $malItem->chapters = $data['chapters'];
            $malItem->publishing = $data['publishing'];
            $malItem->rank = $data['rank'];
            $malItem->score = $data['score'];
            $malItem->scored_by = $data['scored_by'];
            $malItem->popularity = $data['popularity'];
            $malItem->members = $data['members'];
            $malItem->favorites = $data['favorites'];
            $malItem->synopsis = $data['synopsis'];

            $malItem->save();
        }

        $timeElapsedInSeconds = microtime(true) - $start;

        $this->line('...finished. Duration: ' . number_format($timeElapsedInSeconds, 2) . ' seconds.');
        $this->line('');
    }
}
