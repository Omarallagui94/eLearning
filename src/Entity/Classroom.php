<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    /**
     * One Classroom → Many Students
     */
    #[ORM\OneToMany(mappedBy: 'classroom', targetEntity: User::class)]
    private Collection $students;

    /**
     * One Classroom → Many Subjects
     */
    #[ORM\OneToMany(mappedBy: 'classroom', targetEntity: Subject::class)]
    private Collection $subjects;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'classroom')]
    private Collection $exams;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Students
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(User $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClassroom($this);
        }

        return $this;
    }

    public function removeStudent(User $student): self
    {
        if ($this->students->removeElement($student)) {
            if ($student->getClassroom() === $this) {
                $student->setClassroom(null);
            }
        }

        return $this;
    }

    /**
     * Subjects
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(Subject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->setClassroom($this);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            if ($subject->getClassroom() === $this) {
                $subject->setClassroom(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Exam>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): static
    {
        if (!$this->exams->contains($exam)) {
            $this->exams->add($exam);
            $exam->setClassroom($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getClassroom() === $this) {
                $exam->setClassroom(null);
            }
        }

        return $this;
    }
}
