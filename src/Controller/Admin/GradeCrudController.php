<?php

namespace App\Controller\Admin;

use App\Entity\Grade;
use App\Entity\Exam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GradeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Grade::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $addGrade = Action::new('addGrade', 'Add Grade')
            ->linkToRoute('grade_add_for_exam', function (Grade $grade) {
                return ['id' => $grade->getExam()->getId()];
            })
            ->setCssClass('btn btn-success');

        return $actions
            ->add(Crud::PAGE_DETAIL, $addGrade)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW)
            ->disable(Action::EDIT)
            ->disable(Action::DELETE);
    }


    public function configureFields(string $pageName): iterable
    {
        // -----------------------------------------------------
        // PAGE 1: INDEX => SHOW EACH EXAM ONLY ONCE
        // -----------------------------------------------------
        if ($pageName === Crud::PAGE_INDEX) {
            return [
                TextField::new('exam.name', 'Exam')
                    ->formatValue(function ($value, Grade $grade) {
                        return $grade->getExam()->getName();
                    })
            ];
        }

        // -----------------------------------------------------
        // PAGE 2: DETAIL => SHOW LIST OF STUDENT GRADES
        // -----------------------------------------------------
        if ($pageName === Crud::PAGE_DETAIL) {
            return [
                TextField::new('exam.name', 'Exam'),

                CollectionField::new('exam.grades')
                    ->setLabel('Grades')
                    ->setTemplatePath('admin/grade/exam_grades.html.twig'),
            ];
        }

        return [];
    }

}
