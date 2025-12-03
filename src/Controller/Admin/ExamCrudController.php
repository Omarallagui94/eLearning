<?php

namespace App\Controller\Admin;

use App\Entity\Exam;
use App\Entity\User;
use App\Entity\Classroom;
use App\Entity\Subject;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ExamCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined() // optional
            ->setEntityLabelInSingular('Exam')
            ->setEntityLabelInPlural('Exams')
            ->setDefaultSort(['examDate' => 'DESC']);
    }
    public static function getEntityFqcn(): string
    {
        return Exam::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        // Load all users
        $allUsers = $this->container->get('doctrine')
            ->getRepository(User::class)
            ->findAll();

        // Filter users by ROLE_TEACHER in PHP (SAFE)
        $teacherUsers = array_filter($allUsers, fn(User $u) =>
        in_array('ROLE_TEACHER', $u->getRoles())
        );

        return [
            TextField::new('name')->setLabel('Exam Title'),

            DateTimeField::new('examDate')
                ->setLabel('Exam Date')
                ->setRequired(true),

            // Classroom
            AssociationField::new('classroom')
                ->setLabel('Classroom'),

            // Subject
            AssociationField::new('subject')
                ->setLabel('Subject'),

            // Teacher filter FIXED
            AssociationField::new('teacher')
                ->setLabel('Teacher')
                ->setFormTypeOption('choices', $teacherUsers)
                ->setFormTypeOption('choice_label', fn(User $u) =>
                    $u->getFirstName() . ' ' . $u->getLastName()
                ),
        ];
    }
}
