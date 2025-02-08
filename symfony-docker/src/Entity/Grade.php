<?php

namespace App\Entity;

use App\Repository\GradesRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GradesRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?int $grade = null;

    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?DateTime $date_and_time = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy:"grades")]
    #[Groups(["list", "details"])]
    private Student $student;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy:"grades")]
    #[Groups(["list", "details"])]
    private Subject $subject;

    #[ORM\ManyToOne(targetEntity:Teacher::class, inversedBy:"grades")]
    #[Groups(["list", "details"])]
    private Teacher $teacher;

    public function getId(): ?int{
        return $this->id;
    }

    public function getGrade(): ?int{
        return $this->grade;
    }

    public function setGrade(int $grade): static{
        $this->grade = $grade;
        return $this;
    }

    public function getDateAndTime(): ?DateTime{
        return $this->date_and_time;
    }

    public function setDateAndTime(DateTime $date_and_time): static{
        $this->date_and_time = $date_and_time;
        return $this;
    }

    public function getStudent(): Student{
        return $this->student;
    }

    public function setStudent(Student $student): static{
        $this->student = $student;
        return $this;
    }

    public function getSubject(): Subject{
        return $this->subject;
    }

    public function setSubject(Subject $subject): static{
        $this->subject = $subject;
        return $this;
    }

    public function getTeacher(): Teacher{
        return $this->teacher;
    }

    public function setTeacher(Teacher $teacher): static{
        $this->teacher = $teacher;
        return $this;
    }
}
