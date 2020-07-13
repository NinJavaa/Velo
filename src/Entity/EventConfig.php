<?php

namespace App\Entity;

use App\Repository\EventConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventConfigRepository::class)
 */
class EventConfig
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rep;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRep;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $commentPermession;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showInviteList;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isArchived;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $showDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Event", inversedBy="eventConfig")
     */
    private $event;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRep(): ?int
    {
        return $this->rep;
    }

    public function setRep(?int $rep): self
    {
        $this->rep = $rep;

        return $this;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->dateRep;
    }

    public function setDateRep(?\DateTimeInterface $dateRep): self
    {
        $this->dateRep = $dateRep;

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

    public function getCommentPermession(): ?bool
    {
        return $this->commentPermession;
    }

    public function setCommentPermession(?bool $commentPermession): self
    {
        $this->commentPermession = $commentPermession;

        return $this;
    }

    public function getShowInviteList(): ?bool
    {
        return $this->showInviteList;
    }

    public function setShowInviteList(?bool $showInviteList): self
    {
        $this->showInviteList = $showInviteList;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(?bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getShowDate(): ?\DateTimeInterface
    {
        return $this->showDate;
    }

    public function setShowDate(?\DateTimeInterface $showDate): self
    {
        $this->showDate = $showDate;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

}
