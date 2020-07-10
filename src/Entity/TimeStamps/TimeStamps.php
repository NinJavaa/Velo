<?php
namespace App\Entity\TimeStamps;

trait  TimeStamps
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    /**
     * @ORM\PrePersist()
     */
    public function  precreatedAt()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist()
     */
    public function  preupdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PostPersist()
     */
    public function  postcreatedAt()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PostPersist()
     */
    public function  postupdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

}