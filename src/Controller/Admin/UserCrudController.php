<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),

            EmailField::new('email'),

            TextField::new('firstName'),
            TextField::new('lastName'),

            ChoiceField::new('roles')
                ->setLabel('User Roles')
                ->allowMultipleChoices()
                ->renderExpanded()        // Shows checkboxes instead of dropdown
                ->renderAsBadges([
                    'ROLE_USER' => 'success',
                    'ROLE_TEACHER' => 'warning',
                    'ROLE_ADMIN' => 'danger'
                ])
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Teacher' => 'ROLE_TEACHER',
                    'Admin' => 'ROLE_ADMIN',
                ]),

            BooleanField::new('isVerified')->renderAsSwitch(false),

            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
