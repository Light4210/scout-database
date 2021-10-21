<?php


namespace App\Entity;

use App\Entity\Struct;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
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
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $middle_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_of_birth;

    /**
     * @ORM\Column(type="bigint", nullable=true, length=20)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ministry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deal_scan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @OneToOne(targetEntity="Struct", mappedBy="sheaf")
     */
    private $sheafOf;

    /**
     * @ORM\ManyToOne(targetEntity="Struct", inversedBy="members")
     * @ORM\JoinColumn(name="struct_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $struct;

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

    public function getDealScan(): ?string
    {
        return $this->deal_scan;
    }

    public function setDealScan(?string $deal_scan): self
    {
        $this->deal_scan = $deal_scan;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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
}