<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class StudentDashboardController extends AbstractController
{
    #[Route('/student/dashboard', name: 'student_dashboard')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $classroom = $user->getClassroom();
        $subjects = $classroom ? $classroom->getSubjects() : [];

        return $this->render('student/dashboard.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
