<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends User
{
    #[ORM\OneToMany(mappedBy:"teacher", targetEntity: Subject::class)]
    #[Groups(["list", "details"])]
    private Collection $subjects;

    #[ORM\OneToMany(mappedBy:"teacher", targetEntity: Grade::class)]
    #[Groups(["details"])]
    private Collection $grades;

    public function __construct(){
        $this->roles = ["ROLE_TEACHER"];
    }

    public function getSubjects(): Collection{
        return $this->subjects;
    }

    public function setSubjects(Collection $subjects): static{
        $this->subjects = $subjects;
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
