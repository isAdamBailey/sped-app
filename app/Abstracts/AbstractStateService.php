<?php

namespace App\Abstracts;

use App\Models\Chapter;
use App\Models\Section;
use Goutte;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractStateService
{
    protected Model $state;

    protected string $endpoint;

    protected string $title;

    abstract protected function saveChapters(): array;

    abstract protected function saveChapterSections(): array;

    abstract protected function saveSectionContent(): array;

    protected function saveChapter(string $code, string $description)
    {
        return Chapter::firstOrCreate(
            ['code' => trim($code), 'state_id' => $this->state->id, 'title_id' => $this->title],
            ['description' => trim(strip_tags($description))]
        );
    }

    protected function saveSection(Chapter $chapter, array $data): Model
    {
        return Section::firstOrCreate(
            ['code' => trim($data['code']), 'chapter_id' => $chapter->id],
            [
                'state_id' => $this->state->id,
                'description' => trim(strip_tags($data['description'])),
                'url' => $data['url'],
            ],
        );
    }

    protected function fetch(string $endpoint)
    {
        return \Goutte::request('GET', $endpoint);
    }

    protected function response(int $count, string $name): array
    {
        return [
            'count' => $count,
            'message' => $count ? $name.' were imported' : 'no '.$name.' were imported',
        ];
    }
}
