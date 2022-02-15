<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Table
 * @ORM\Entity(repositoryClass=AttachmentsRepository::class)
 */
class Attachments
{
    const DEAL_PATH =  'images/deals';
    const DEAL_TYPE = 'deal';
    const PHOTO_TYPE = 'photo';
    const PHOTO_PATH = 'images/photos';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $extension;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var string
     */
    protected $updatedAt;

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param string $title
     * @param string $extension
     * @return static
     */
    public static function create(
        string $title,
        string $extension,
    ): self {
        $attachment = new self();
        $attachment->title = $title;
        $attachment->extension = $extension;

        return $attachment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }
}