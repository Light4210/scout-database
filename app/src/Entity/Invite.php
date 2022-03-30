<?php

namespace App\Entity;

use DateTime;
use App\Repository\InviteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InviteRepository::class)
 */
class Invite
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $expiration_date;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ministry;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @param $email
     * @param $expiration_date
     * @param $created_at
     * @param $role
     * @param $ministry
     * @param $token
     * @throws \Exception
     */
    public function __construct($email, $ministry)
    {
        $expirationDate=Date('y:m:d', strtotime('+10 days'));

        $this->email = $email;
        $this->expiration_date = $expirationDate;
        $this->created_at = date('y:m:d');
        $this->ministry = $ministry;
        $this->token = bin2hex(random_bytes(16));
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeImmutable
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeImmutable $expiration_date): self
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getMinistry(): ?string
    {
        return $this->ministry;
    }

    public function setMinistry(?string $ministry): self
    {
        $this->ministry = $ministry;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
