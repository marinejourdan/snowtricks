<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'text')]
    private ?string $type;

    #[ORM\ManyToOne(targetEntity: 'Trick', fetch: 'EAGER', inversedBy: 'gallery')]
    private ?Trick $trick;

    #[ORM\OneToOne(targetEntity: 'User', fetch: 'EAGER', mappedBy: 'avatar')]
    private ?User $user;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $fileName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $videoUrl;

    private $uploadedFile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
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

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    public function setUploadedFile($UploadedFile): self
    {
        $this->uploadedFile = $UploadedFile;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl($videoUrl): self
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }
}
