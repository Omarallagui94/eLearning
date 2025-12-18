<?php

namespace App\Controller\Admin;

use App\Entity\Exam;
use App\Entity\Lesson;
use App\Entity\Question;
use App\Entity\Subject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_TEACHER')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/teacher/admin', name: 'teacher_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Teacher Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Teaching');
        yield MenuItem::linkToCrud('Subjects', 'fa fa-book', Subject::class);
        yield MenuItem::linkToCrud('Lessons', 'fa fa-chalkboard-teacher', Lesson::class);
        yield MenuItem::linkToCrud('Exams', 'fa fa-file-alt', Exam::class);
        yield MenuItem::linkToCrud('Questions', 'fa fa-question-circle', Question::class);
    }
}
