<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                )))
            ->add('firstname')
            ->add('name')
            ->add('email', TextareaType::class, array(
                'attr' => array(
                    'readonly' => true,
                )))
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',

                'format' => 'yyyy-MM-dd',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}