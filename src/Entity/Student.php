<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @UniqueEntity("matricule", message="Un Student ayant ce matricule existe déjà !")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="Le nom du student doit être obligatoire.")
     * @Assert\Length(min=2, minMessage="Le nom du student doit avoir 2 caractère au minimum.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le prenom du student doit être obligatoire.")
     * @Assert\Length(min=2, minMessage="Le prénom du student doit avoir 2 caractère au minimum.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $age;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="student")
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, minMessage="Le matricule doit avoir au 3 caractères.")
     */
    private $matricule;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    // /**
    //  * @ORM\PrePersist
    //  * @ORM\PreUpdate
    //  */
    // public function initializeDateNaissance(){
    //     if(empty($this->dateNaissance)){
    //         $dateNaissance = new DateTime();
    //     }
    // }

    // public function getDateNaissance(): ?\DateTimeInterface
    // {
    //     return $this->dateNaissance;
    // }

    // public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    // {
    //     $this->dateNaissance = $dateNaissance;

    //     return $this;
    // }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setStudent($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getStudent() === $this) {
                $image->setStudent(null);
            }
        }

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

}