<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity('name', 'slug')]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'trick.name.not_blank')]
    private $name = '';

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message: 'trick.description.not_blank')]
    private $description;

    #[ORM\OneToMany(targetEntity: 'Message', cascade: ['remove'], fetch: 'EAGER', mappedBy: 'trick')]
    private $messages;

    #[ORM\OneToMany(targetEntity: 'Media', cascade: ['persist', 'remove'], fetch: 'LAZY', mappedBy: 'trick')]
    private $gallery;

    #[ORM\ManyToOne(targetEntity: 'Group', fetch: 'EAGER', inversedBy: 'tricks')]
    private $group;

    #[ORM\Column(type: 'string', length: 100)]
    private $slug;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    public function setMessages(Collection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    public function getGallery(): ?Collection
    {
        return $this->gallery;
    }

    public function setGallery(?Collection $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFirstMedia()
    {
        return $this->getGallery()->first();
    }
}
