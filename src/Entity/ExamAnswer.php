<?php

namespace App\Entity;

use App\Repository\ExamAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamAnswerRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_attempt_question', columns: ['attempt_id', 'question_id'])]
class ExamAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExamAttempt $attempt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExamQuestion $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)] // answer can be text or json string, nullable? user said text/json.
    private ?string $answer = null;

    #[ORM\Column]
    private ?float $pointsAwarded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttempt(): ?ExamAttempt
    {
        return $this->attempt;
    }

    public function setAttempt(?ExamAttempt $attempt): static
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function getQuestion(): ?ExamQuestion
    {
        return $this->question;
    }

    public function setQuestion(?ExamQuestion $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getPointsAwarded(): ?float
    {
        return $this->pointsAwarded;
    }

    public function setPointsAwarded(float $pointsAwarded): static
    {
        $this->pointsAwarded = $pointsAwarded;

        return $this;
    }
}
