<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["list", "details"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["list", "details"])]
    private ?string $Day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(["list", "details"])]
    private ?\DateTimeInterface $time = null;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: "schedule_days")]
    #[Groups(["list", "details"])]
    private Subject $subject;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy:"schedule_days")]
    #[Groups(["list"])]
    private Group $group;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->Day;
    }

    public function setDay(string $Day): static
    {
        $this->Day = $Day;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getSubject(): Subject{
        return $this->subject;
    }

    public function setSubject(Subject $subject): static{
        $this->subject = $subject;
        return $this;
    }

    public function getGroup(): Group{
        return $this->group;
    }

    public function setGroup(Group $group): static{
        $this->group = $group;
        return $this;
    }
}
