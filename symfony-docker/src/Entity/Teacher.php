<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends User
{
    #[ORM\OneToMany(mappedBy:"teacher", targetEntity: Subject::class)]
    private Collection $subjects;

    public function getSubjects(): Collection{
        return $this->subjects;
    }

    public function setSubjects(Collection $subjects): static{
        $this->subjects = $subjects;
        return $this;
    }
}
