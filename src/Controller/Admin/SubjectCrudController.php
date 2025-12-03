<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Entity\User;
use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SubjectCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    // Inject Doctrine
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Subject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Load users
        $allUsers = $this->em->getRepository(User::class)->findAll();

        // Filter only ROLE_TEACHER
        $teacherUsers = array_filter($allUsers, function(User $u) {
            return in_array('ROLE_TEACHER', $u->getRoles());
        });

        return [
            TextField::new('name'),
            TextEditorField::new('description')->hideOnIndex(),

            /*
            |-----------------------------
            | CLASSROOM SELECT
            |-----------------------------
            */
            AssociationField::new('classroom')
                ->setLabel('Classroom'),

            /*
            |-----------------------------
            | TEACHER SELECT (filtered)
            |-----------------------------
            */
            AssociationField::new('teacher')
                ->setLabel('Teacher')
                ->setFormTypeOption('choice_label', fn(User $u) =>
                    $u->getFirstName() . ' ' . $u->getLastName()
                )
                ->setFormTypeOption('choices', $teacherUsers),
        ];
    }
}

