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
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descritpion;

    #[ORM\OneToMany(targetEntity: "Message", cascade: ["all"], fetch: "EAGER", mappedBy: "trick")]
    private $messages;

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

    public function getDescritpion(): ?string
    {
        return $this->descritpion;
    }

    public function setDescritpion(?string $descritpion): self
    {
        $this->descritpion = $descritpion;

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

}
