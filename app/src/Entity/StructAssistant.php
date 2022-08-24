<?php

namespace App\Entity;

use App\Repository\StructAssistantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=StructAssistantRepository::class)
 */
class StructAssistant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Struct::class, inversedBy="structAssistants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $struct;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="structAssistant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct(User|UserInterface $user, Struct $struct)
    {
        $this->user = $user;
        $this->struct = $struct;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStruct(): ?Struct
    {
        return $this->struct;
    }

    public function setStruct(?Struct $struct): self
    {
        $this->struct = $struct;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
