<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateRepository::class)]
class Template
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $topic = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $lastUpdatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'templates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, TemplateTag>
     */
    #[ORM\OneToMany(targetEntity: TemplateTag::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $templateTags;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $questions;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $likes;

    /**
     * @var Collection<int, FormSubmit>
     */
    #[ORM\OneToMany(targetEntity: FormSubmit::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $formSubmits;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'template', orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->templateTags = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->formSubmits = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastUpdatedAt(): ?\DateTime
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(\DateTime $lastUpdatedAt): static
    {
        $this->lastUpdatedAt = $lastUpdatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, TemplateTag>
     */
    public function getTemplateTags(): Collection
    {
        return $this->templateTags;
    }

    public function addTemplateTag(TemplateTag $templateTag): static
    {
        if (!$this->templateTags->contains($templateTag)) {
            $this->templateTags->add($templateTag);
            $templateTag->setTemplate($this);
        }

        return $this;
    }

    public function removeTemplateTag(TemplateTag $templateTag): static
    {
        if ($this->templateTags->removeElement($templateTag)) {
            // set the owning side to null (unless already changed)
            if ($templateTag->getTemplate() === $this) {
                $templateTag->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setTemplate($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTemplate() === $this) {
                $question->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setTemplate($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTemplate() === $this) {
                $like->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FormSubmit>
     */
    public function getFormSubmits(): Collection
    {
        return $this->formSubmits;
    }

    public function addFormSubmit(FormSubmit $formSubmit): static
    {
        if (!$this->formSubmits->contains($formSubmit)) {
            $this->formSubmits->add($formSubmit);
            $formSubmit->setTemplate($this);
        }

        return $this;
    }

    public function removeFormSubmit(FormSubmit $formSubmit): static
    {
        if ($this->formSubmits->removeElement($formSubmit)) {
            // set the owning side to null (unless already changed)
            if ($formSubmit->getTemplate() === $this) {
                $formSubmit->setTemplate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTemplate($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTemplate() === $this) {
                $comment->setTemplate(null);
            }
        }

        return $this;
    }
}
