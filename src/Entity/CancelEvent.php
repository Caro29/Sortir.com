<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CancelEventRepository")
 */
class CancelEvent
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
    private $reason;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Event", cascade={"persist", "remove"})
     */
    private $relation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getRelation(): ?Event
    {
        return $this->relation;
    }

    public function setRelation(?Event $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
