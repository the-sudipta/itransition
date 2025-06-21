<?php

namespace App\Entity;

use App\Repository\TemplateTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateTagRepository::class)]
class TemplateTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    #[ORM\ManyToOne(inversedBy: 'templateTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Template $template = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): static
    {
        $this->template = $template;

        return $this;
    }
}
