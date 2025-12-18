<?php

namespace App\Controller;

use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/teacher/subject')]
#[IsGranted('ROLE_TEACHER')]
class TeacherSubjectController extends AbstractController
{
    #[Route('/{id}', name: 'teacher_subject_show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {
        // Ensure the teacher owns this subject
        if ($subject->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not teach this subject.');
        }

        return $this->render('teacher/subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }
}
