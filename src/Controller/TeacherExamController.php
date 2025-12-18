<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\Subject;
use App\Form\ExamType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/teacher')]
#[IsGranted('ROLE_TEACHER')]
class TeacherExamController extends AbstractController
{
    #[Route('/subjects/{id}/exams', name: 'teacher_subject_exams', methods: ['GET'])]
    public function index(Subject $subject): Response
    {
        if ($subject->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not teach this subject.');
        }

        return $this->render('teacher/exams/index.html.twig', [
            'subject' => $subject,
            'exams' => $subject->getExams(),
        ]);
    }

    #[Route('/subjects/{id}/exams/new', name: 'teacher_exam_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Subject $subject, EntityManagerInterface $entityManager): Response
    {
        if ($subject->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not teach this subject.');
        }

        $exam = new Exam();
        $exam->setSubject($subject);
        $exam->setTeacher($this->getUser());
        // Default to subject classroom if available, user can change it
        if ($subject->getClassroom()) {
            $exam->setClassroom($subject->getClassroom());
        }

        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exam);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_subject_exams', ['id' => $subject->getId()]);
        }

        return $this->render('teacher/exams/new.html.twig', [
            'exam' => $exam,
            'form' => $form,
            'subject' => $subject,
        ]);
    }

    #[Route('/exams/{id}', name: 'teacher_exam_show', methods: ['GET'])]
    public function show(Exam $exam): Response
    {
        if ($exam->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not own this exam.');
        }

        return $this->render('teacher/exams/show.html.twig', [
            'exam' => $exam,
            'subject' => $exam->getSubject(), // Pass subject for breadcrumbs if needed
        ]);
    }

    #[Route('/exams/{id}/edit', name: 'teacher_exam_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exam $exam, EntityManagerInterface $entityManager): Response
    {
        if ($exam->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not own this exam.');
        }

        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('teacher_subject_exams', ['id' => $exam->getSubject()->getId()]);
        }

        return $this->render('teacher/exams/new.html.twig', [
            'exam' => $exam,
            'form' => $form,
            'subject' => $exam->getSubject(),
        ]);
    }

    #[Route('/exams/{id}/delete', name: 'teacher_exam_delete', methods: ['POST'])]
    public function delete(Request $request, Exam $exam, EntityManagerInterface $entityManager): Response
    {
        if ($exam->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not own this exam.');
        }

        if ($this->isCsrfTokenValid('delete'.$exam->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_subject_exams', ['id' => $exam->getSubject()->getId()]);
    }
}
