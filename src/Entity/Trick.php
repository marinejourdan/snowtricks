<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\Collection;
use App\Entity\Group;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity('name','slug')]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message:'trick.name.not_blank')]
    private $name='prout';

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank(message:'trick.description.not_blank')]
    private $description;

    #[ORM\OneToMany(targetEntity: "Message", cascade: ["remove"], fetch: "EAGER", mappedBy: "trick")]
    private $messages;

   #[ORM\OneToMany(targetEntity: "Media", cascade: ["persist", "remove"], fetch: "EAGER", mappedBy: "trick")]
    private $medias;

    #[ORM\ManyToOne(targetEntity: "Group", fetch: "EAGER", inversedBy: "tricks")]
    private $group;

    #[ORM\Column(type: 'string', length: 100)]
    private $slug;

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

   public function getMedias(): ?Collection
   {
        return $this->medias;
   }

   public function setMedias(Collection $medias): self
    {
       $this->medias = $medias;

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


}
