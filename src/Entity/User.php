<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\Column(type: 'string', length: 25)]
    private $email;

    #[ORM\Column(type: 'string', length: 100)]
    private $password;

    private $plainPassword;

    #[ORM\OneToMany(targetEntity: "Message", cascade: ["all"], fetch: "EAGER", mappedBy: "author")]
    private $messages;

    //#[ORM\OneToMany(targetEntity: "media", cascade: ["all"], fetch: "EAGER", mappedBy: "user")]
   // private $medias;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
  //  public function getMedias(): ?Collection
    //{
       // return $this->medias;
    //}

  //  public function setMedias(Collection $medias): self
    //{
      //  $this->medias = $medias;

        //return $this;
    //}
}
