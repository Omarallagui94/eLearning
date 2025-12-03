<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $student = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $exam = null;

    #[ORM\Column]
    private ?float $score = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'grades')]
    private ?self $grade = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'grade')]
    private Collection $grades;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?User
    {
        return $this->student;
    }

    public function setStudent(?User $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getGrade(): ?self
    {
        return $this->grade;
    }

    public function setGrade(?self $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(self $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setGrade($this);
        }

        return $this;
    }

    public function removeGrade(self $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getGrade() === $this) {
                $grade->setGrade(null);
            }
        }

        return $this;
    }
}
