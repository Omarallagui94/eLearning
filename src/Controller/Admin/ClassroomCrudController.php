<?php

namespace App\Controller\Admin;

use App\Entity\Classroom;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ClassroomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Classroom::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),

            TextField::new('name'),

            TextEditorField::new('description')
                ->hideOnIndex(),

            AssociationField::new('students')
                ->setLabel("Assign Students")
                ->setFormTypeOption("by_reference", false)
                ->setFormTypeOption("choice_label", fn(User $u) =>
                    $u->getFirstName() . ' ' . $u->getLastName()
                )
                ->setFormTypeOption("query_builder", function ($repo) {
                    return $repo->createQueryBuilder('u')
                        // Only students (ROLE_USER)
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_USER%');
                }),
        ];
    }
}
