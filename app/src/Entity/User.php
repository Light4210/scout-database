<?php


namespace App\Entity;

use App\Trait\CreatedAtTrait;
use App\Trait\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use CreatedAtTrait;
    use UpdatedAtTrait;

    const MALE = 'male';
    const FEMALE = 'female';

    const STATUS_ACTIVE = 'active';
    const STATUS_PASSIVE = 'passive';

    const PRIORITY_STANDARD = 'standart';
    const PRIORITY_NATIONAL_COUNCIL = 'council';

    const ROLE_TRAVELLER = 'traveller';
    const ROLE_SCOUT = 'scout';
    const ROLE_WOLVES = 'wolves';

    const ROLE_ADMIN = 'admin';

    const ACTIVE_MINISTRY = [
        'troopLeader' => [
            'name' => 'Курінний/на',
            'gender' => [
                self::MALE => 'Курінний',
                self::FEMALE => 'Курінна'
            ],
            'slug' => 'troopLeader',
            'struct_slug' => Struct::TROOP_SLUG,
            'access' => self::PRIORITY_STANDARD,
            'membersRole' => self::ROLE_SCOUT,
            'colorClass' => 'troop'
        ],
        'president' => [
            'name' => 'Президент',
            'slug' => 'president',
            'struct_slug' => 'none',
            'access' => self::PRIORITY_NATIONAL_COUNCIL,
            'membersRole' => 'none',
            'colorClass' => 'national-council'
        ],
        'admin' => [
            'name' => 'Адмін',
            'slug' => 'admin',
            'struct_slug' => 'none',
            'access' => self::PRIORITY_NATIONAL_COUNCIL,
            'membersRole' => 'none',
            'colorClass' => 'national-council'
        ],
        'sheaf' => [
            'name' => 'Шеф',
            'slug' => 'sheaf',
            'struct_slug' => Struct::CIRCLE_SLUG,
            'access' => self::PRIORITY_STANDARD,
            'membersRole' => self::ROLE_TRAVELLER,
            'colorClass' => 'circle'
        ],
        'akela' => [
            'name' => 'Акела',
            'struct_slug' => Struct::COMMUNITY_SLUG,
            'slug' => 'akela',
            'access' => self::PRIORITY_STANDARD,
            'membersRole' => self::ROLE_WOLVES,
            'colorClass' => 'community'
        ],
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=62, unique=true, nullable=false)
     * @Assert\Email
     * @Assert\Length(
     *      min = 5,
     *      max = 62,
     *      minMessage = "Your email must be at least {{ limit }} characters long",
     *      maxMessage = "Your email cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json", nullable=true, length=100)
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true, length=255)
     * @Assert\Length(
     *      min = 6,
     *      max = 100,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private string $password;

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
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your surname must be at least {{ limit }} characters long",
     *      maxMessage = "Your surname cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $surname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your middle name must be at least {{ limit }} characters long",
     *      maxMessage = "Your middle name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $middle_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $date_of_birth;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      min = 9,
     *      max = 15,
     *      minMessage = "Your phone number must be at least {{ limit }} characters long",
     *      maxMessage = "Your phone number cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $phoneNumber;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $status;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $ministry;

    /**
     * @OneToOne(targetEntity="Attachments")
     * @JoinColumn(name="deal_scan_id", referencedColumnName="id", nullable=true)
     * @Assert\File( maxSize="5M", mimeTypes={"application/pdf", "application/x-pdf"} )
     */
    private $deal_scan;

    /**
     * @ORM\Column(type="string", length=95, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 95,
     *      minMessage = "Your address must be at least {{ limit }} characters long",
     *      maxMessage = "Your address cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private ?string $role;

    /**
     * @OneToOne(targetEntity="Attachments")
     * @JoinColumn(name="photo_id", referencedColumnName="id", nullable=true)
     * @Assert\File( maxSize="5M", mimeTypes={"image/*"} )
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @OneToOne(targetEntity="Struct", mappedBy="sheaf")
     */
    private mixed $sheafOf;

    /**
     * @ORM\ManyToOne(targetEntity="Struct", inversedBy="members")
     * @ORM\JoinColumn(name="struct_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $struct;

    /**
     * @ORM\OneToMany(targetEntity=Notification::class, mappedBy="targetUser", orphanRemoval=true)
     */
    private $notifications;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="author")
     */
    private $games;

    /**
     * @param string|null $email
     * @param array $roles
     * @param string|null $name
     * @param string|null $surname
     * @param string|null $middle_name
     * @param \DateTimeInterface|null $date_of_birth
     * @param string|null $phoneNumber
     * @param string|null $status
     * @param string|null $ministry
     * @param string|null $address
     * @param string|null $role
     * @param $created_at
     * @param $updated_at
     */

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
        $this->created_at =  new \DateTimeImmutable();
        $this->games = new ArrayCollection();
    }

    public function withAllData(?string $email, array $roles, ?string $name, ?string $surname, ?string $middle_name, ?\DateTimeInterface $date_of_birth, ?string $phoneNumber, ?string $status, ?string $ministry, ?string $address, ?string $role)
    {
        $this->email = $email;
        $this->roles = $roles;
        $this->name = $name;
        $this->surname = $surname;
        $this->middle_name = $middle_name;
        $this->date_of_birth = $date_of_birth;
        $this->phoneNumber = $phoneNumber;
        $this->status = $status;
        $this->ministry = $ministry;
        $this->address = $address;
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getStruct()
    {
        return $this->struct;
    }

    /**
     * @return mixed
     */
    public function getSheafOf()
    {
        return $this->sheafOf;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    public function setMiddleName(string $middle_name): self
    {
        $this->middle_name = $middle_name;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     * @return mixed
     */
    public function getDealScan()
    {
        return $this->deal_scan;
    }

    /**
     * @param mixed $deal_scan
     */
    public function setDealScan($deal_scan)
    {
        $this->deal_scan = $deal_scan;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @param mixed $struct
     */
    public function setStruct($struct): void
    {
        $this->struct = $struct;
    }

    /**
     * @param mixed $sheafOf
     */
    public function setSheafOf($sheafOf): void
    {
        $this->sheafOf = $sheafOf;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setTargetUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getTargetUser() === $this) {
                $notification->setTargetUser(null);
            }
        }

        return $this;
    }

    public function removeUserFromStruct() :User{
        $this->setStruct(null);
        $this->setStatus(User::STATUS_PASSIVE);
        return $this;
    }
    public function getUserPermision(){
        $ministry = $this->getMinistry();
        return self::ACTIVE_MINISTRY[$ministry]['access'];
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setAuthor($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getAuthor() === $this) {
                $game->setAuthor(null);
            }
        }

        return $this;
    }
}