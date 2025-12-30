<?php

namespace App\Controller;

use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class StudentSubjectController extends AbstractController
{
    #[Route('/student/subject/{id}', name: 'student_subject_show')]
    public function show(Subject $subject): Response
    {
        // Security check: Ensure the student belongs to the classroom that owns this subject
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        
        if ($subject->getClassroom() !== $user->getClassroom()) {
            throw $this->createAccessDeniedException('You are not enrolled in this subject.');
        }

        return $this->render('student/subject/show.html.twig', [
            'subject' => $subject,
            'lessons' => $subject->getLessons(),
        ]);
    }
}
