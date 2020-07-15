<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $categoryName;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection|Step[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Step", inversedBy="categories", fetch="LAZY")
     * @ORM\JoinTable(
     *  name="category_step_catalogue",
     *  joinColumns={
     *      @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="step_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $stepsCatalogue;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="category" , fetch="LAZY")
     */
    protected $events;


    public function __construct()
    {
        $this->stepsCatalogue = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getStepsCatalogue(): Collection
    {
        return $this->stepsCatalogue;
    }

    public function addStepsCatalogue(Step $stepsCatalogue): self
    {
        if (!$this->stepsCatalogue->contains($stepsCatalogue)) {
            $this->stepsCatalogue[] = $stepsCatalogue;
        }

        return $this;
    }

    public function removeStepsCatalogue(Step $stepsCatalogue): self
    {
        if ($this->stepsCatalogue->contains($stepsCatalogue)) {
            $this->stepsCatalogue->removeElement($stepsCatalogue);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getCategory() === $this) {
                $event->setCategory(null);
            }
        }

        return $this;
    }
}
