<?php

namespace App\Controller\Admin;

use App\Entity\Grade;
use App\Entity\User;
use App\Entity\Exam;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class GradeCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string {
        return Grade::class;
    }

    public function configureFields(string $pageName): iterable {

        return [

            AssociationField::new('exam')->setLabel('Exam'),

            AssociationField::new('student')
                ->setLabel('Student')
                ->setFormTypeOption('choice_label', fn(User $u) =>
                    $u->getFirstName() . ' ' . $u->getLastName()
                ),

            NumberField::new('score')->setLabel('Score'),
        ];
    }
}
