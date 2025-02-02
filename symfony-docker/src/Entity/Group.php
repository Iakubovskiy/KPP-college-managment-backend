<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Name = null;

    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'group')]
    private Collection $students;

    #[ORM\OneToMany(targetEntity: Scheduale::class, mappedBy:'group')]
    private Collection $sheduale_days;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }
    
    public function getStudents(): Collection{
        return $this->students;
    }

    public function setStudents(Collection $students): static{
        $this->students = $students;
        return $this;
    }

    public function getShedualeDays(): Collection{
        return $this->sheduale_days;
    }

    public function setShedualeDays(Collection $sheduale_days): static{
        $this->sheduale_days = $sheduale_days;
        return $this;
    }
}
