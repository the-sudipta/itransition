<?php

namespace App\Dto;

class TemplateCardDto
{
    public function __construct(
        private int    $id,
        private string $title,
        private string $description,
        private string $imageUrl,
        private int    $likesCount,
        private int    $commentsCount
    ) {}

    public function getId(): int            { return $this->id; }
    public function getTitle(): string      { return $this->title; }
    public function getDescription(): string{ return $this->description; }
    public function getImageUrl(): string   { return $this->imageUrl; }
    public function getLikesCount(): int    { return $this->likesCount; }
    public function getCommentsCount(): int { return $this->commentsCount; }
}
