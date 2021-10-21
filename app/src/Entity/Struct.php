<?php

namespace App\Entity;

use App\Repository\StructRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

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
            'name' => 'troops',
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="sheafOf")
     * @ORM\JoinColumn(name="sheaf_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $sheaf;


    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="struct")
     */
    private $members;

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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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
    public function getCreatedAt()
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
}
