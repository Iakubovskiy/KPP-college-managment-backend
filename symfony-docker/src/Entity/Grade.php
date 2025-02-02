<?php

namespace App\Entity;

use App\Repository\GradesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradesRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $grade = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy:"grades")]
    private Student $student;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy:"grades")]
    private Subject $subject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getStudent(): Student{
        return $this->student;
    }

    public function setStudent(Student $student): static{
        $this->student = $student;
        return $this;
    }

    public function getTeacher(): Subject{
        return $this->subject;
    }

    public function setTeacher(Subject $subject): static{
        $this->subject = $subject;
        return $this;
    }
}
