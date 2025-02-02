<?php

namespace App\Entity;

use App\Repository\SchedualeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchedualeRepository::class)]
class Scheduale 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Day = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: "schedual_days")]
    private Subject $subject;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy:"sheduale_days")]
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
