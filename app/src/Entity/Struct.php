<?php

namespace App\Entity;

use App\Trait\CreatedAtTrait;
use App\Trait\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Repository\StructRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StructRepository::class)
 */
class Struct
{

    use CreatedAtTrait;
    use UpdatedAtTrait;

    const TROOP_SLUG = 'troop';
    const CIRCLE_SLUG = 'circle';
    const COMMUNITY_SLUG = 'community';

    const STRUCT = [
        'troop' => [
            'name' => 'Troop',
            'slug' => self::TROOP_SLUG,
            'sheaf' => User::ACTIVE_MINISTRY['troopLeader'],
            'membersRole' => User::ROLE_SCOUT,
        ],
        'community' => [
            'name' => 'Community',
            'slug' => self::COMMUNITY_SLUG,
            'sheaf' => User::ACTIVE_MINISTRY['akela'],
            'membersRole' => User::ROLE_WOLVES,
        ],
        'circle' => [
            'name' => 'Circle',
            'slug' => self::CIRCLE_SLUG,
            'sheaf' => User::ACTIVE_MINISTRY['sheaf'],
            'membersRole' => User:: ROLE_TRAVELLER,
        ]
    ];

    const SELECTED_STATUS_DISABLED = 'disabled';
    const REQUEST_STATUS_PENDING = 'pending';
    const REQUEST_STATUS_NONE = 'none';

    private $requestStatus = 'none';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=35, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 35,
     *      minMessage = "Your city name must be at least {{ limit }} characters long",
     *      maxMessage = "Your city name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=95, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 95,
     *      minMessage = "Your address must be at least {{ limit }} characters long",
     *      maxMessage = "Your address cannot be longer than {{ limit }} characters"
     * )
     */
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

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSheaf()
    {
        return $this->sheaf;
    }

    /**
     * @param string $requestStatus
     */
    public function setRequestStatus(string $requestStatus): void
    {
        $this->requestStatus = $requestStatus;
    }

    /**
     * @return string
     */
    public function getRequestStatus(): string
    {
        return $this->requestStatus;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
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
     * @return ArrayCollection
     */
    public function getActiveMembers(): array
    {
        $members =  $this->members;
        $activeMembers = [];
        foreach ($members as $member){
            if($member->getStatus() == User::STATUS_ACTIVE){
                $activeMembers[] = $member;
            }
        }
        return $activeMembers;
    }

    /**
     * @return ArrayCollection
     */
    public function getPassiveMembers(): array
    {
        $members =  $this->members;
        $activeMembers = [];
        foreach ($members as $member){
            if($member->getStatus() == User::STATUS_PASSIVE){
                $activeMembers[] = $member;
            }
        }
        return $activeMembers;
    }

    /**
     * @param ArrayCollection $activeMembers
     */
    public function setActiveMembers(ArrayCollection $activeMembers): void
    {
        $this->activeMembers = $activeMembers;
    }

    /**
     * @param ArrayCollection $passiveMembers
     */
    public function setPassiveMembers(ArrayCollection $passiveMembers): void
    {
        $this->passiveMembers = $passiveMembers;
    }
}
