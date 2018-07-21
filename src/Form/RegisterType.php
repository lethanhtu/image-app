<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email',EmailType::class)
        ->add('username',TextType::class)
        ->add('full_name',TextType::class)
        ->add('password',RepeatedType::class,[
            'type' => PasswordType::class,
            'first_options'=>['label'=>'Password'],
            'second_options'=>['label'=>'Password confirmation']
        ])
        ->add('terms_accepted', CheckboxType::class, [
            'mapped' => false,
            'label'=>'Accept']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }
}
