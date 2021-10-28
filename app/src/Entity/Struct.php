<?php

namespace App\Entity;

use App\Repository\StructRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StructRepository::class)
 */
class Struct
{
    const STRUCT_NAMES = [
        'troop' => 'troop',
        'community' => 'community',
        'circle' => 'circle'
    ];

    const STRUCT = [
        self::STRUCT_NAMES['troop'] => [
            'name' => 'troop',
            'sheaf' => User::MINISTRY['troopLeader']
        ],
        self::STRUCT_NAMES['community'] => [
            'name' => 'community',
            'sheaf' => User::MINISTRY['akela']
        ],
        self::STRUCT_NAMES['circle'] => [
            'name' => 'circle',
            'sheaf' => User::MINISTRY['sheaf']
        ]
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     * @Assert\Choice(choices=Struct::STRUCT_NAMES, message="Choose a valid struct type.")
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=35, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 35,
     *      minMessage = "Your city name must be at least {{ limit }} characters long",
     *      maxMessage = "Your city name cannot be longer than {{ limit }} characters"
     * )     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=95, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 95,
     *      minMessage = "Your address must be at least {{ limit }} characters long",
     *      maxMessage = "Your address cannot be longer than {{ limit }} characters"
     * )     */
    private ?string $address;

    /**
     * @ORM\Column(type="decimal", nullable=true, length=12)
     * @Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Latitude must be at least {{ limit }} characters long",
     *      maxMessage = "Latitude be longer than {{ limit }} characters"
     * )
     */
    private ?float $latitude;

    /**
     * @ORM\Column(type="decimal", nullable=true, length=12)
     * @Assert\Length(
     *      min = 2,
     *      max = 12,
     *      minMessage = "Longitude must be at least {{ limit }} characters long",
     *      maxMessage = "Longitude cannot be longer than {{ limit }} characters"
     * )
     *
     */
    private ?float $longitude;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private mixed $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private mixed $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="sheafOf")
     * @ORM\JoinColumn(name="sheaf_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private mixed $sheaf;


    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="struct")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=TransferUser::class, mappedBy="target_struct", orphanRemoval=true)
     */
    private $transfersOnPending;

    /**
     * @ORM\OneToMany(targetEntity=TransferUser::class, mappedBy="current_struct", orphanRemoval=true)
     */
    private $transfers;

    /**
     * @return mixed
     */
    public function getSheaf()
    {
        return $this->sheaf;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->transfersOnPending = new ArrayCollection();
        $this->transfers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    public function setLatitude(?int $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?int
    {
        return $this->longitude;
    }

    public function setLongitude(?int $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): mixed
    {
        return $this->created_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $sheaf
     */
    public function setSheaf($sheaf): void
    {
        $this->sheaf = $sheaf;
    }

    /**
     * @param mixed $members
     */
    public function setMembers($members): void
    {
        $this->members = $members;
    }

    /**
     * @return Collection|TransferUser[]
     */
    public function getTransfersOnPending(): Collection
    {
        return $this->transfersOnPending;
    }

    public function addTransfersOnPending(TransferUser $transfersOnPending): self
    {
        if (!$this->transfersOnPending->contains($transfersOnPending)) {
            $this->transfersOnPending[] = $transfersOnPending;
            $transfersOnPending->setTargetStruct($this);
        }

        return $this;
    }

    public function removeTransfersOnPending(TransferUser $transfersOnPending): self
    {
        if ($this->transfersOnPending->removeElement($transfersOnPending)) {
            // set the owning side to null (unless already changed)
            if ($transfersOnPending->getTargetStruct() === $this) {
                $transfersOnPending->setTargetStruct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TransferUser[]
     */
    public function getTransfers(): Collection
    {
        return $this->transfers;
    }

    public function addTransfer(TransferUser $transfer): self
    {
        if (!$this->transfers->contains($transfer)) {
            $this->transfers[] = $transfer;
            $transfer->setCurrentStruct($this);
        }

        return $this;
    }

    public function removeTransfer(TransferUser $transfer): self
    {
        if ($this->transfers->removeElement($transfer)) {
            // set the owning side to null (unless already changed)
            if ($transfer->getCurrentStruct() === $this) {
                $transfer->setCurrentStruct(null);
            }
        }

        return $this;
    }
}
