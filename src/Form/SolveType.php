<?php

namespace App\Form;

use App\Entity\Solve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextareaType::class, ['attr' => ['class' => 'form-control', 'rows' => 7]])
            ->add('add', SubmitType::class, ['attr' => ['class' => 'btn btn-success'], 'label' => 'Отправить на проверку']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Solve::class,
        ));
    }
}