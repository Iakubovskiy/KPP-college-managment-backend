<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    #[Groups(["list", "details"])]

    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["list", "details"])]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'group')]
    #[Groups(["details"])]
    private Collection $students;

    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy:'group')]
    #[Groups(["details"])]
    private Collection $schedule_days;

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
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStudents(): Collection{
        return $this->students;
    }

    public function setStudents(Collection $students): static{
        $this->students = $students;
        return $this;
    }

    public function getScheduleDays(): Collection{
        return $this->schedule_days;
    }

    public function setScheduleDays(Collection $schedule_days): static{
        $this->schedule_days = $schedule_days;
        return $this;
    }
}
