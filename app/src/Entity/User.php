<?php


namespace App\Entity;

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
    const STATUS = [
        'active' => 'active',
        'passive' => 'passive',
    ];

    const ROLES = [
        'traveller' => 'traveller',
        'scout' => 'scout',
        'wolvies' => 'wolvies',
    ];

    const PRIORITY = [
        'standard' => 10,
        'nationalCouncil' => 50,
    ];

    const MINISTRY = [
        'troopLeader' => [
            'name' => 'troopLeader',
            'access' => self::PRIORITY['standard'],
            'sheafOf'=> Struct::STRUCT_NAMES['troop'],
            'membersRole' => self::ROLES['scout']
        ],
        'sheaf' => [
            'name' => 'sheaf',
            'access' => self::PRIORITY['standard'],
            'sheafOf'=> Struct::STRUCT_NAMES['circle'],
            'membersRole' => self::ROLES['traveller']
        ],
        'akela' => [
            'name' => 'akela',
            'access' => self::PRIORITY['standard'],
            'sheafOf'=> Struct::STRUCT_NAMES['community'],
            'membersRole' => self::ROLES['wolvies']
        ],
        'president' => [
            'name' => 'president',
            'access' => self::PRIORITY['nationalCouncil'],
            'sheafOf'=> Struct::STRUCT_NAMES['troop'],
            'membersRole' => self::ROLES['scout']
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
     */    private string $password;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters"
     * )
     */    private ?string $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your surname must be at least {{ limit }} characters long",
     *      maxMessage = "Your surname cannot be longer than {{ limit }} characters"
     * )
     */    private ?string $surname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your middle name must be at least {{ limit }} characters long",
     *      maxMessage = "Your middle name cannot be longer than {{ limit }} characters"
     * )
     */    private ?string $middle_name;

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
     */    private ?string $phone_number;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, options={"default" : "active"})
     * @Assert\Choice(choices=User::STATUS, message="Choose a valid status")
     */
    private ?string $status;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Choice(choices=User::MINISTRY, message="Choose a valid ministry")
     */
    private ?string $ministry;

    /**
     * @OneToOne(targetEntity="Attachments")
     * @JoinColumn(name="deal_scan_id", referencedColumnName="id", nullable=true)
     * @Assert\File( maxSize="1M", mimeTypes={"application/pdf", "application/x-pdf"} )
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
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Choice(choices=User::ROLES, message="Choose a valid role")
     */
    private ?string $role;

    /**
     * @OneToOne(targetEntity="Attachments")
     * @JoinColumn(name="photo_id", referencedColumnName="id", nullable=true)
     * @Assert\File( maxSize="4M", mimeTypes={"image/*"} )
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
     * @ORM\OneToMany(targetEntity=Notifications::class, mappedBy="user", orphanRemoval=true)
     */
    private $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
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

    public function getPhoneNumber(): ?int
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?int $phone_number): self
    {
        $this->phone_number = $phone_number;

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

    public function getRole(): ?string
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
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

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }
}