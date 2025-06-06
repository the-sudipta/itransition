<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]  // ← needed so PrePersist will fire
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The nickname chosen by this user.
     * Only letters and spaces are allowed.
     */
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Nickname cannot be blank.')]
    #[Assert\Length(
        max: 50,
        maxMessage: 'Nickname cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(
        pattern: '/^[\p{L} ]+$/u',
        message: 'Nickname must contain only letters and spaces.'
    )]
    private ?string $nickname = null;

    /**
     * This user’s role within the session:
     *   • "creator"
     *   • "editor"
     *   • "viewer"
     */
    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank(message: 'Role cannot be blank.')]
    #[Assert\Choice(
        choices: ['creator', 'editor', 'viewer'],
        message: 'Role must be one of "creator", "editor" or "viewer".'
    )]
    private ?string $role = 'editor';

    /**
     * Timestamp when this user joined the session.
     * If null on persist, a PrePersist method will set it to “now.”
     */
    #[ORM\Column]
    #[Assert\NotNull(message: 'JoinedAt must not be null.')]
    #[Assert\Type(
        type: \DateTimeInterface::class,
        message: 'JoinedAt must be a valid DateTime object (e.g. "Y-m-d H:i:s").'
    )]
    private ?\DateTime $joinedAt = null;

    /**
     * The Session entity to which this user belongs.
     *
     * Many User → One Session.
     *
     * This is the owning side.
     */
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Session reference cannot be null.')]
    private ?Session $session = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getJoinedAt(): ?\DateTime
    {
        return $this->joinedAt;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function setJoinedAt(\DateTime $joinedAt): static
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): static
    {
        $this->session = $session;

        return $this;
    }


    /**
     * If joinedAt is still null when inserting, default to “now.”
     *
     * @ORM\PrePersist
     */
    #[ORM\PrePersist]
    public function initializeJoinedAt(): void
    {
        if (null === $this->joinedAt) {
            $this->joinedAt = new \DateTime();
        }
    }

}
