<?php

namespace App\Entity;

use DateTime;
use DateInterval;
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

    public function __construct()
    {
        $expirationDate=new \DateTimeImmutable();
        $expirationDate = $expirationDate->add(new DateInterval('P10D'));
        $this->expiration_date = $expirationDate;
        $this->role = User::ROLE_TRAVELLER;
        $this->created_at =  new \DateTimeImmutable();
        $this->token = bin2hex(random_bytes(25));
    }

    public function isExpired(): bool
    {
        if($this->getExpirationDate() < new \DateTimeImmutable()){
            return true;
        }
        return false;
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
