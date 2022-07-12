<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Asserts\NotBlank]private $name='prout';

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(targetEntity: "Message", cascade: ["all"], fetch: "EAGER", mappedBy: "trick")]
    private $messages;

   #[ORM\OneToMany(targetEntity: "Media", cascade: ["all"], fetch: "EAGER", mappedBy: "trick")]
    private $medias;

    #[ORM\ManyToOne(targetEntity: "group", cascade: ["all"], fetch: "EAGER", inversedBy: "tricks")]
    private $group;


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

    public function setGroup(Collection $group): self
    {
        $this->group = $group;

        return $this;
    }

}
