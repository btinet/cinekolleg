<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NotificationRepository::class)
 */
class Notification
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notifications")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=NotificationTemplate::class, inversedBy="notifications")
     */
    private $sources;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
    }

    public function __toString()
    {
        return 'notification';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, NotificationTemplate>
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(NotificationTemplate $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
        }

        return $this;
    }

    public function removeSource(NotificationTemplate $source): self
    {
        $this->sources->removeElement($source);

        return $this;
    }
}
