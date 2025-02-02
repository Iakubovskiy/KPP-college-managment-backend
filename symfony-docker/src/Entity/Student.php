<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateOfBirth = null;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'students')]
    private Group $group;

    #[ORM\OneToMany(targetEntity: Grade::class, mappedBy:'student')]
    private Collection $grades;

    public function __construct(){
        $this->roles = ["ROLE_STUDENT"];
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->DateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $DateOfBirth): static
    {
        $this->DateOfBirth = $DateOfBirth;

        return $this;
    }

    public function getGroup(): ?Group{
        return $this->group;
    }

    public function setGroup(Group $group): self{
        $this->group = $group;
        return $this;
    }

    public function getGrades(): Collection{
        return $this->grades;
    }

    public function setGrades(Collection $grades): static{
        $this->grades = $grades;
        return $this;
    }
}
