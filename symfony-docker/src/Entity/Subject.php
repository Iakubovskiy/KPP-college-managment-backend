<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    #[Groups(["list", "details"])]
    private ?string $Name = null;

    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?int $hoursPerWeek = null;

    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy: 'subject')]
    #[Groups(["details"])]
    private Collection $schedule_days;

    #[ORM\ManyToOne(targetEntity: Teacher::class, inversedBy:'subjects')]
    #[Groups(["list", "details"])]
    private Teacher $teacher;

    #[ORM\OneToMany(targetEntity:Grade::class, mappedBy:'subject')]
    #[Groups(["details"])]
    private Collection $grades;

    public function __construct()
    {
        $this->schedule_days = new ArrayCollection();
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

    public function getHoursPerWeek(): ?int{
        return $this->hoursPerWeek;
    }

    public function setHoursPerWeek(int $hoursPerWeek): static{
        $this->hoursPerWeek = $hoursPerWeek;
        return $this;
    }

    public function getSchedules(): Collection{
        return $this->schedule_days;
    }

    public function setSchedules(Collection $schedual_days): static{
        $this->schedule_days = $schedual_days;
        return $this;
    }

    public function getTeachers(): Teacher{
        return $this->teacher;
    }

    public function setTeachers(Teacher $teacher): static{
        $this->teacher = $teacher;
        return $this;
    }

    public function getGrades(): ?Collection{
        return $this->grades;
    }

    public function setGrades(Collection $grades): static{
        $this->grades = $grades;
        return $this;
    }

    public function mapFromOneObjectToAnother(Subject $subject): static{
        $this->Name = $subject->getName();
        $this->hoursPerWeek = $subject->getHoursPerWeek();
        $this->teacher = $subject->getTeachers();
        $this->grades = $subject->getGrades();
        $this->schedule_days = $subject->getSchedules();
        return $this;
    }

}
