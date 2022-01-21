<?php

namespace App\Abstracts;

use App\Models\Chapter;
use App\Models\Section;
use Goutte;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractLawService
{
    protected Model $state;

    protected string $endpoint;

    protected string $title;

    abstract public function saveChapters(): array;

    abstract public function saveChapterSections(): array;

    abstract public function saveSectionContent(): array;

    protected function saveChapter(string $code, string $description)
    {
        $description = trim(strip_tags($description));

        $chapter = Chapter::firstOrCreate(
            ['code' => trim($code), 'state_id' => $this->state->id, 'title_id' => $this->title],
            ['description' => $description, 'code_title' => $this->state->code_title]
        );

        if ($description !== $chapter->description) {
            $chapter->update(['description' => $description]);
        }

        return $chapter;
    }

    protected function saveSection(\stdClass $chapter, array $data): Model
    {
        $description = trim(strip_tags($data['description']));

        $section = Section::firstOrCreate(
            ['code' => trim($data['code']), 'chapter_id' => $chapter->id],
            [
                'code_title' => $this->state->code_title,
                'state_id' => $this->state->id,
                'description' => $description,
                'url' => $data['url'],
            ],
        );

        if ($description !== $section->description) {
            $section->update(['description' => $description]);
        }

        return $section;
    }

    protected function fetch(string $endpoint)
    {
        return \Goutte::request('GET', $endpoint);
    }

    protected function response(int $storedCount, int $foundCount, string $name): array
    {
        $message = $storedCount !== $foundCount
            ? 'We had '.$storedCount.' stored, but found '.$foundCount.'. '
            : '';
        $message .= $foundCount ? $foundCount.' '.$name.' were imported' : 'no '.$name.' were imported';

        return [
            'count' => $foundCount,
            'message' => $message,
        ];
    }
}
