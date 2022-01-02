<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use Illuminate\Console\Command;

class FetchLaws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:state-laws';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command fetches state laws and persists them to the database';

    protected string $endpoint = '';
    protected string $title = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->endpoint = 'https://app.leg.wa.gov/RCW/default.aspx?cite=';
        $this->title = '28A';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // fetching all chapters
        $content = \Goutte::request('GET', $this->endpoint.$this->title);

        $responseArray = [];
        $content->filter('table tr')->each(function ($node) use (&$responseArray) {
            $code = $node->filter('td a')->first()->text();
            $description = $node->filter('td')->last()->text();

            $chapter = Chapter::firstOrCreate(['code' => $code]);

            if($chapter->description !== $description) {
                $chapter->update(['description' => $description]);
            }

            $responseArray[] = [
                'code' => $code,
                'description' => $description
            ];

        });

        $this->info(count($responseArray).' chapters have been imported');
        return 0;
    }
}
