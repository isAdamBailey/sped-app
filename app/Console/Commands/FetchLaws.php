<?php

namespace App\Console\Commands;

use App\Services\WashingtonLawService;
use Illuminate\Console\Command;

class FetchLaws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:laws {state?} {title?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command accepts a state name, then fetches laws from that state and persists them to the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $service =$this->stateService($this->argument('title'));

        $chapters = $service->saveChapters();
        if ($chapters['count'] === 0) {
            $this->error('Could not find chapters in the page');
            // email admins
        }
        $this->info($chapters['message']);

        $sections = $service->saveChapterSections();
        if ($sections['count'] === 0) {
            $this->error('Could not find sections in the page');
            // email admins
        }
        $this->info($sections['message']);

        return 0;
    }

    private function stateService(?string $title) {
        return match ($this->argument('state')) {
            'washington' => new WashingtonLawService($title),
            default => new WashingtonLawService($title),
        };
    }
}
