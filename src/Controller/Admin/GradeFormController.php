<?php

namespace App\Controller\Admin;

use App\Entity\Exam;
use App\Entity\Grade;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GradeFormController extends AbstractController
{
    #[Route('/admin/grade/add/{id}', name: 'grade_add_for_exam')]
    public function addGradeForExam(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $exam = $em->getRepository(Exam::class)->find($id);
        if (!$exam) {
            throw $this->createNotFoundException("Exam not found.");
        }

        // Get ONLY students in this exam's classroom
        $students = $exam->getClassroom()->getStudents();

        $grade = new Grade();
        $grade->setExam($exam);

        $form = $this->createFormBuilder($grade)
            ->add('student', ChoiceType::class, [
                'choices' => $students,
                'choice_label' => fn(User $u) => $u->getFirstName() . ' ' . $u->getLastName(),
                'label' => 'Student'
            ])
            ->add('score', NumberType::class, [
                'label' => 'Score'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($grade);
            $em->flush();

            $this->addFlash('success', 'Grade added successfully.');

            return $this->redirectToRoute('easyadmin', [
                'crudAction' => 'detail',
                'crudControllerFqcn' => GradeCrudController::class,
                'entityId' => $grade->getId(),
            ]);

        }

        return $this->render('admin/grade/add_grade.html.twig', [
            'form' => $form->createView(),
            'exam' => $exam,
        ]);
    }
}
