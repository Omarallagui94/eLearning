<?php

namespace App\Form;

use App\Entity\ExamQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ExamQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionText', TextareaType::class, [
                'label' => 'Question Text',
                'attr' => ['rows' => 3, 'class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Question Type',
                'choices' => [
                    'Multiple Choice (MCQ)' => 'mcq',
                    'True / False' => 'tf',
                    'Short Answer' => 'short',
                ],
                'attr' => ['class' => 'form-select', 'id' => 'question_type_select'],
                'constraints' => [new NotBlank()]
            ])
            ->add('points', NumberType::class, [
                'label' => 'Points',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank(), new Positive()]
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position (Order)',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            // Conditional UI fields
            ->add('choicesRaw', TextareaType::class, [
                'label' => 'Choices (One per line) - For MCQ only',
                'mapped' => false,
                'required' => false,
                'attr' => ['rows' => 4, 'class' => 'form-control', 'id' => 'choices_field'],
                'help' => 'Enter each choice on a new line.'
            ])
            ->add('correctAnswer', TextType::class, [
                'label' => 'Correct Answer',
                'help' => 'For MCQ: Must match one of the choices exactly. For T/F: Enter "true" or "false". For Short: The exact expected text.',
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
        ;

        // Transform choicesRaw (string) to choices (array) handles in Controller or generic logic?
        // Better to handle in the form data mapping if possible, but 'choices' in entity is array.
        // Let's rely on the controller to manually process choicesRaw into choices array for simplicity/robustness,
        // or just use a transformer here if we mapped it to 'choices'.
        // Since I mapped it false, I'll do it in Controller.
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExamQuestion::class,
        ]);
    }
}
