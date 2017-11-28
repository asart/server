<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class TaskSolveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('solve', TextareaType::class, ['attr' => ['class' => 'form-control', 'rows' => 7]])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-success'], 'label' => 'Отправить на проверку']);
    }
}