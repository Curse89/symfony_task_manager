<?php

namespace App\Form;

use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
				'label' => 'Название',
	            'attr' => ['class' => 'form-control']

            ])
            ->add('active', CheckboxType::class, [
				'label' => 'Активность',
				'required' => false,
	            'attr' => [
					'checked' => 'checked',
		            'class' => 'form-check-input'
		            ]
            ])
	        ->add('save', SubmitType::class, [
				'label' => 'Сохранить',
		        'attr' => [
					'class' => 'btn btn-outline-success'
		        ]
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
