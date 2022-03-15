<?php

namespace App\Console\Commands;

use App\Abstracts\AbstractLawService;
use App\Mail\AdminEmail;
use App\Models\User;
use App\Services\IDEAService;
use App\Services\OregonLawService;
use App\Services\WashingtonLawService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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
            $this->sendAdminEmails($service->getMessage());

            return 1;
        }

        $fullMessage = '';
        $chapters = $service->saveChapters();
        if (0 === $chapters['count']) {
            $this->sendAdminEmails('Could not find chapters in the page. '.$chapters['message']);

            return 1;
        }

        $fullMessage .= $chapters['message'].'\r\n';
        $this->info($chapters['message']);

        $sections = $service->saveChapterSections();
        if (0 === $sections['count']) {
            $this->sendAdminEmails('Could not find sections in the page. '.$sections['message']);

            return 1;
        }

        $fullMessage .= $sections['message'].'\r\n';
        $this->info($sections['message']);

        $content = $service->saveSectionContent();

        $fullMessage .= $content['message'].'\r\n';
        $this->info($content['message']);

        $this->sendAdminEmails('Command '.$this->name.' ran from '.config('app.name').'. '.$fullMessage);
        return 0;
    }

    private function stateService(?string $title): AbstractLawService|Exception
    {
        return match ($this->argument('state')) {
            'washington' => new WashingtonLawService($title),
            'oregon' => new OregonLawService($title),
            'idea' => new IDEAService($title),
            default => new Exception('Enter a valid state')
        };
    }

    private function sendAdminEmails(string $message)
    {
        $this->error($message);

        foreach (User::siteAdminEmails() as $email) {
            Mail::to($email)->send(new AdminEmail($message));
        }
    }
}
