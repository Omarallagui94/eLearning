<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\ExamAnswer;
use App\Entity\ExamAttempt;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class StudentExamController extends AbstractController
{
    #[Route('/student/exams', name: 'student_exams')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $classroom = $user->getClassroom();

        // Fetch exams assigned to the student's classroom
        // Fetch exams assigned to the student's classroom
        $allExams = $classroom ? $classroom->getExams() : [];

        $now = new \DateTime();
        $upcomingExams = [];
        $activeExams = [];
        $pastExams = [];

        foreach ($allExams as $exam) {
            $start = $exam->getExamDate();
            $duration = $exam->getDurationMinutes();
            
            if (!$start) {
                continue; // Skip if no date set
            }

            $end = (clone $start)->modify("+{$duration} minutes");

            if ($now < $start) {
                $upcomingExams[] = $exam;
            } elseif ($now >= $start && $now <= $end) {
                $activeExams[] = $exam;
            } else {
                $pastExams[] = $exam;
            }
        }

        return $this->render('student/exam/index.html.twig', [
            'upcomingExams' => $upcomingExams,
            'activeExams' => $activeExams,
            'pastExams' => $pastExams,
        ]);
    }

    #[Route('/student/exam/{id}/take', name: 'student_exam_take')]
    public function take(Exam $exam, Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($exam->getClassroom() !== $user->getClassroom()) {
             throw $this->createAccessDeniedException('You are not authorized to take this exam.');
        }

        $now = new \DateTime();
        $start = $exam->getExamDate();
        $duration = $exam->getDurationMinutes();
        
        // 1. Check if exam has started
        if ($start && $now < $start) {
            $this->addFlash('warning', 'This exam is not yet available.');
            return $this->redirectToRoute('student_exams');
        }

        // 2. Check if exam has ended (strict check based on global duration)
        if ($start && $duration) {
            $end = (clone $start)->modify("+{$duration} minutes");
            // Add a small buffer (e.g., 2 minutes) for submission latency
            if ($now > $end->modify('+2 minutes')) {
                 $this->addFlash('danger', 'This exam has ended.');
                 return $this->redirectToRoute('student_exams');
            }
        }

        // Check for existing attempt
        $attempt = $entityManager->getRepository(ExamAttempt::class)->findOneBy([
            'exam' => $exam,
            'student' => $user
        ]);

        if (!$attempt) {
            $attempt = new ExamAttempt();
            $attempt->setExam($exam);
            $attempt->setStudent($user);
            $attempt->setStartedAt(new \DateTimeImmutable());
            $attempt->setStatus('started');
            $entityManager->persist($attempt);
            $entityManager->flush();
        }

        if ($attempt->getStatus() === 'completed') {
             return $this->redirectToRoute('student_exam_result', ['id' => $attempt->getId()]);
        }
        
        // Handle Form Submission
        if ($request->isMethod('POST')) {
            $submittedAnswers = $request->request->all('answers');
            $totalScore = 0;

            foreach ($exam->getQuestions() as $question) {
                $studentAnswerText = $submittedAnswers[$question->getId()] ?? null;
                
                // Create ExamAnswer
                $examAnswer = new ExamAnswer();
                $examAnswer->setAttempt($attempt);
                $examAnswer->setQuestion($question);
                $examAnswer->setAnswer($studentAnswerText); // Store what they typed/selected

                // Auto-grading logic
                $pointsAwarded = 0;
                $correctAnswerRaw = $question->getCorrectAnswer();
                
                // Simple string comparison for now (Case-insensitive)
                // For MCQ, checkbox might be array, but here we assume single choice (radio) or text.
                if ($studentAnswerText && strcasecmp(trim($studentAnswerText), trim($correctAnswerRaw)) === 0) {
                    $pointsAwarded = $question->getPoints();
                }

                $examAnswer->setPointsAwarded($pointsAwarded);
                $entityManager->persist($examAnswer);
                
                $totalScore += $pointsAwarded;
            }

            $attempt->setSubmittedAt(new \DateTimeImmutable());
            $attempt->setStatus('completed');
            $attempt->setScore($totalScore);
            
            $entityManager->persist($attempt);
            $entityManager->flush();

            return $this->redirectToRoute('student_exam_result', ['id' => $attempt->getId()]);
        }
        
        // Calculate remaining seconds
        // Logic: Min(Exam End Time - Now, Attempt Start + Duration - Now)
        // If Exam has fixed global window:
        $remainingSeconds = 0;
        if ($start && $duration) {
            $globalEndTime = (clone $start)->modify("+{$duration} minutes");
            $attemptEndTime = $attempt->getStartedAt()->modify("+{$duration} minutes");
            
            // The real deadline is the earlier of the two (usually same if started promptly, but if late start, global end wins)
             // Actually, strict exam usually implies: You define duration. 
             // If "Exam Date" is a fixed start time for everyone, then everyone finishes at Start + Duration.
             // If "Exam Date" is just "Available From", then it's Attempt + Duration.
             // Based on "Upcoming" logic using activeExams window, it seems implied "Fixed Global Window". 
             // Let's stick to Global End Time for now as it's safer/strict.
             
             $finalEndTime = $globalEndTime; 
             $remainingSeconds = $finalEndTime->getTimestamp() - (new \DateTime())->getTimestamp();
        }
        
        return $this->render('student/exam/take.html.twig', [
            'exam' => $exam,
            'remainingSeconds' => max(0, $remainingSeconds)
        ]);
    }

    #[Route('/student/exam/result/{id}', name: 'student_exam_result')]
    public function result(ExamAttempt $attempt): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($attempt->getStudent() !== $user) {
            throw $this->createAccessDeniedException('Access Denied.');
        }

        return $this->render('student/exam/result.html.twig', [
            'attempt' => $attempt,
            'exam' => $attempt->getExam(),
        ]);
    }
}
