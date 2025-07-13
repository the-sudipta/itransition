<?php

namespace App\Entity;

use App\Repository\SalesforceAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesforceAccountRepository::class)]
class SalesforceAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $accessToken = null;

    #[ORM\Column(length: 255)]
    private ?string $refreshToken = null;

    #[ORM\Column(length: 255)]
    private ?string $instanceUrl = null;

    #[ORM\Column]
    private ?\DateTime $issuedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $expiresAt = null;

    #[ORM\OneToOne(inversedBy: 'salesforceAccount', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): static
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getInstanceUrl(): ?string
    {
        return $this->instanceUrl;
    }

    public function setInstanceUrl(string $instanceUrl): static
    {
        $this->instanceUrl = $instanceUrl;

        return $this;
    }

    public function getIssuedAt(): ?\DateTime
    {
        return $this->issuedAt;
    }

    public function setIssuedAt(\DateTime $issuedAt): static
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTime $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
