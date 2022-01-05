<?php

namespace App\Console\Commands;

use App\Services\OregonLawService;
use App\Services\WashingtonLawService;
use Exception;
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
        $service = $this->stateService($this->argument('title'));
        if (is_a($service, Exception::class)) {
            $this->error($service->getMessage());
            return 1;
        }

        $chapters = $service->saveChapters();
        if (0 === $chapters['count']) {
            $this->error('Could not find chapters in the page');
        // email admins
            return 1;
        } else {
            $this->info($chapters['message']);
        }

        $sections = $service->saveChapterSections();
        if (0 === $sections['count']) {
            $this->error('Could not find sections in the page');
        // email admins
            return 1;
        } else {
            $this->info($sections['message']);
        }

        $content = $service->saveSectionContent();
        $this->info($content['message']);

        return 0;
    }

    private function stateService(?string $title): OregonLawService|Exception|WashingtonLawService
    {
        return match ($this->argument('state')) {
            'washington' => new WashingtonLawService($title),
            'oregon' => new OregonLawService($title),
            default => new Exception('Enter a valid state')
        };
    }
}
