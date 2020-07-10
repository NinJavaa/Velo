<?php

namespace App\Entity;

use App\Repository\ArchiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveRepository::class)
 */
class Archive
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $archType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $archContenet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArchType(): ?string
    {
        return $this->archType;
    }

    public function setArchType(string $archType): self
    {
        $this->archType = $archType;

        return $this;
    }

    public function getArchContenet(): ?string
    {
        return $this->archContenet;
    }

    public function setArchContenet(string $archContenet): self
    {
        $this->archContenet = $archContenet;

        return $this;
    }
}
