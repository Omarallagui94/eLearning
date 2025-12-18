<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TeacherDashboardController extends AbstractController
{
    #[Route('/teacher/dashboard', name: 'teacher_dashboard')]
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Only teachers can access this dashboard.');
        }

        // Sort subjects by name
        $subjects = $user->getSubjects()->toArray();
        usort($subjects, function ($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });

        return $this->render('teacher/dashboard.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
