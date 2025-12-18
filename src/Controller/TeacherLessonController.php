<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Subject;
use App\Form\LessonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/teacher/subjects/{id}/lessons')]
#[IsGranted('ROLE_TEACHER')]
class TeacherLessonController extends AbstractController
{
    #[Route('', name: 'teacher_subject_lessons', methods: ['GET'])]
    public function index(Subject $subject): Response
    {
        if ($subject->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not teach this subject.');
        }

        return $this->render('teacher/lessons/index.html.twig', [
            'subject' => $subject,
            'lessons' => $subject->getLessons(),
        ]);
    }

    #[Route('/new', name: 'teacher_lesson_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Subject $subject, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($subject->getTeacher() !== $this->getUser()) {
             throw $this->createAccessDeniedException('You do not teach this subject.');
        }

        $lesson = new Lesson();
        $lesson->setSubject($subject);
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/lessons',
                        $newFilename
                    );
                    $lesson->setFilePath('uploads/lessons/'.$newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload file.');
                }
            }

            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('teacher_subject_lessons', ['id' => $subject->getId()]);
        }

        return $this->render('teacher/lessons/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
            'subject' => $subject,
        ]);
    }
}
