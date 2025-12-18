<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\ExamQuestion;
use App\Form\ExamQuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/teacher/exams')]
#[IsGranted('ROLE_TEACHER')]
class TeacherQuestionController extends AbstractController
{
    #[Route('/{id}/questions/new', name: 'teacher_exam_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Exam $exam, EntityManagerInterface $entityManager): Response
    {
        // Ownership check
        if ($exam->getSubject()->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not own this exam.');
        }

        $question = new ExamQuestion();
        $question->setExam($exam);
        
        // Default position logic (optional)
        $question->setPosition($exam->getQuestions()->count() + 1);

        $form = $this->createForm(ExamQuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Handle Logic based on Type
            $type = $question->getType();
            $choicesRaw = $form->get('choicesRaw')->getData();

            if ($type === 'mcq') {
                if (empty($choicesRaw)) {
                    $this->addFlash('error', 'MCQ questions require choices.');
                    // Return to form without saving? or just let validation fail? 
                    // Since mapped=false, constraint wasn't on form. Let's return. // Actually relying on controller logic better.
                    return $this->render('teacher/questions/new.html.twig', [
                        'question' => $question,
                        'form' => $form,
                        'exam' => $exam,
                    ]);
                }
                
                // Parse lines to array
                 $choices = array_filter(array_map('trim', explode("\n", $choicesRaw)));
                 $question->setChoices(array_values($choices)); // Reset keys

                 // Validate correctAnswer is in choices
                 if (!in_array($question->getCorrectAnswer(), $choices)) {
                    $this->addFlash('error', 'The correct answer must match one of the choices exactly.');
                    return $this->render('teacher/questions/new.html.twig', [
                        'question' => $question,
                        'form' => $form,
                        'exam' => $exam,
                    ]);
                 }

            } elseif ($type === 'tf') {
                 $question->setChoices(null);
                 // Normalize boolean text? User said "true / false".
                 $ans = strtolower(trim($question->getCorrectAnswer()));
                 if (!in_array($ans, ['true', 'false'])) {
                     $this->addFlash('error', 'True/False answer must be "true" or "false".');
                     return $this->render('teacher/questions/new.html.twig', [
                        'question' => $question,
                        'form' => $form,
                        'exam' => $exam,
                    ]);
                 }
                 $question->setCorrectAnswer($ans); // ensure normalized
            } else {
                // short
                $question->setChoices(null);
            }

            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question added successfully.');
            return $this->redirectToRoute('teacher_exam_show', ['id' => $exam->getId()]);
        }

        return $this->render('teacher/questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
            'exam' => $exam,
        ]);
    }
}
