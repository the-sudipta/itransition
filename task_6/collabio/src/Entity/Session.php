<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\HasLifecycleCallbacks]  // ← needed so PrePersist will fire
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Session ID must not be blank.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Session ID cannot be longer than {{ limit }} characters.'
    )]
    private ?string $sessionId = null;

    /**
     * The nickname of the user who created this session.
     * Only letters and spaces allowed, no digits or special characters.
     */
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Creator nickname cannot be blank.')]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Creator nickname cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(
        pattern: '/^[\p{L} ]+$/u',
        message: 'Creator nickname must contain only letters and spaces.'
    )]
    private ?string $creatorNickname = null;

    /**
     * ENUM: “active”, “inactive”, or “closed”.
     */
    #[Assert\NotBlank(message: 'Status cannot be blank.')]
    #[Assert\Choice(
        choices: ['active', 'inactive', 'closed'],
        message: 'Status must be one of "active", "inactive" or "closed".'
    )]
    #[ORM\Column(length: 10)]
    private ?string $status = null;

    /**
     * When the session was created. If still null on persist, PrePersist will set it to “now.”
     */
    #[Assert\NotNull(message: 'CreatedAt must not be null.')]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: 'CreatedAt must be a valid DateTime object (e.g. "Y-m-d H:i:s").'
    )]
    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'session', orphanRemoval: true)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getCreatorNickname(): ?string
    {
        return $this->creatorNickname;
    }

    public function setCreatorNickname(string $creatorNickname): static
    {
        $this->creatorNickname = $creatorNickname;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSession($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSession() === $this) {
                $user->setSession(null);
            }
        }

        return $this;
    }


    /**
     * @ORM\PrePersist
     */
    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        if (null === $this->createdAt) {
            $this->createdAt = new \DateTime();
        }
    }

}
