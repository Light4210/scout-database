<?php

namespace App\Entity;

use App\Repository\TransferUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TransferUserRepository::class)
 */
class TransferUser
{
    const STATUS = [
      'pending' => 0,
      'success' => 10,
      'declined'=> 20,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Choice(choices=TransferUser::STATUS, message="Choose a valid status.")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transfers", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Struct::class, inversedBy="transfersOnPending")
     * @ORM\JoinColumn(nullable=false)
     */
    private $target_struct;

    /**
     * @ORM\ManyToOne(targetEntity=Struct::class, inversedBy="transfers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $current_struct;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTargetStruct(): ?Struct
    {
        return $this->target_struct;
    }

    public function setTargetStruct(?Struct $target_struct): self
    {
        $this->target_struct = $target_struct;

        return $this;
    }

    public function getCurrentStruct(): ?Struct
    {
        return $this->current_struct;
    }

    public function setCurrentStruct(?Struct $current_struct): self
    {
        $this->current_struct = $current_struct;

        return $this;
    }
}
