<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $answer_text = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Option $choosenOption = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FormSubmit $formSubmit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswerText(): ?string
    {
        return $this->answer_text;
    }

    public function setAnswerText(string $answer_text): static
    {
        $this->answer_text = $answer_text;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getChoosenOption(): ?Option
    {
        return $this->choosenOption;
    }

    public function setChoosenOption(?Option $choosenOption): static
    {
        $this->choosenOption = $choosenOption;

        return $this;
    }

    public function getFormSubmit(): ?FormSubmit
    {
        return $this->formSubmit;
    }

    public function setFormSubmit(?FormSubmit $formSubmit): static
    {
        $this->formSubmit = $formSubmit;

        return $this;
    }
}
