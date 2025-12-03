<?php

namespace App\Controller\Admin;

use App\Entity\Grade;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subject;
use App\Entity\Exam;


#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Render your custom dashboard
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('E-Learning Admin Panel')
            ->renderContentMaximized()
            ->renderSidebarMinimized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('User Management');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Classrooms', 'fa fa-school', \App\Entity\Classroom::class);
        yield MenuItem::linkToCrud('Subjects', 'fa fa-book', Subject::class);
        yield MenuItem::linkToCrud('Exams', 'fa fa-file-alt', Exam::class);
        yield MenuItem::linkToCrud('Grades', 'fas fa-star', Grade::class);


        // Later you'll add:
        // yield MenuItem::linkToCrud('Courses', 'fa fa-book', Course::class);
        // yield MenuItem::linkToCrud('Lessons', 'fa fa-list', Lesson::class);
        // yield MenuItem::linkToCrud('Assignments', 'fa fa-tasks', Assignment::class);
    }
}
