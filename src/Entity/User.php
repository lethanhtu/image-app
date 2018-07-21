<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface
{
    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\JoinTable(name="`like`")
     */
    private $imageLike;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\Column(type="smallint")
     */
    private $role;

    public function __construct()
    {
        $this->imageUpload = new ArrayCollection();
        $this->imageLike = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created_date = new \DateTime();
        $this->role = self::ROLE_USER;
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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return;
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

}
