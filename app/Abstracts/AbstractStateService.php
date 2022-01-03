<?php

namespace App\Abstracts;

use App\Models\Chapter;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Goutte;

abstract class AbstractStateService
{
    protected Model $state;
    protected string $endpoint;
    protected string $title;

    abstract protected function saveChapters(): array;

    abstract protected function saveChapterSections(): array;

    protected function saveChapter(string $code, string $description)
    {
        return Chapter::firstOrCreate(
            ['code' => $code, 'state_id' => $this->state->id],
            ['description' => strip_tags($description)]
        );
    }

    protected function saveSection(Chapter $chapter, string $code, string $description): Model
    {
        return Section::firstOrCreate(
            ['code' => $code, 'chapter_id' => $chapter->id],
            [
                'state_id' => $this->state->id,
                'description' => strip_tags($description)
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
            'message' => $count ? $count.' '.$name.' were imported' : 'no '.$name.' were imported',
        ];
    }
}