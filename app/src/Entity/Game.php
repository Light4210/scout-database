<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    const STATUS_APPROVE = 'approved';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';
    const BIG_GAME = 'bigGame';
    const SIMPLE_GAME = 'simpleGame';
    const GAME_NAMES = [
        self::BIG_GAME => 'велика гра',
        self::SIMPLE_GAME => 'звичайна гра'
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $min_users;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $max_users;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Attachments::class, cascade={"persist", "remove"})
     */
    private $ilustration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMinUsers(): ?int
    {
        return $this->min_users;
    }

    public function setMinUsers(int $min_users): self
    {
        $this->min_users = $min_users;

        return $this;
    }

    public function getMaxUsers(): ?int
    {
        return $this->max_users;
    }

    public function setMaxUsers(?int $max_users): self
    {
        $this->max_users = $max_users;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIlustration()
    {
        return $this->ilustration;
    }

    public function setIlustration($ilustration): self
    {
        $this->ilustration = $ilustration;

        return $this;
    }

    public function approve(){
        $this->setStatus(self::STATUS_APPROVE);
    }

    public function delete(){
        $this->setStatus(self::STATUS_DELETED);
    }
}
