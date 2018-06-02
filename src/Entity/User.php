<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="uploadedBy")
     */
    private $imageUpload;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", mappedBy="likedBy")
     * @ORM\JoinTable(name="like")
     */
    private $imageLike;

    public function __construct()
    {
        $this->imageUpload = new ArrayCollection();
        $this->imageLike = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImageUpload(): Collection
    {
        return $this->imageUpload;
    }

    public function addImageUpload(Image $imageUpload): self
    {
        if (!$this->imageUpload->contains($imageUpload)) {
            $this->imageUpload[] = $imageUpload;
            $imageUpload->setUploadedBy($this);
        }

        return $this;
    }

    public function removeImageUpload(Image $imageUpload): self
    {
        if ($this->imageUpload->contains($imageUpload)) {
            $this->imageUpload->removeElement($imageUpload);
            // set the owning side to null (unless already changed)
            if ($imageUpload->getUploadedBy() === $this) {
                $imageUpload->setUploadedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImageLike(): Collection
    {
        return $this->imageLike;
    }

    public function addImageLike(Image $imageLike): self
    {
        if (!$this->imageLike->contains($imageLike)) {
            $this->imageLike[] = $imageLike;
            $imageLike->addLikedBy($this);
        }

        return $this;
    }

    public function removeImageLike(Image $imageLike): self
    {
        if ($this->imageLike->contains($imageLike)) {
            $this->imageLike->removeElement($imageLike);
            $imageLike->removeLikedBy($this);
        }

        return $this;
    }
}
