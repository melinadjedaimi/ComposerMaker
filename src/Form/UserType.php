<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(['message' => 'L\'email est obligatoire']),
                    new Email(['message' => 'Email invalide']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'votre@email.com']
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe (laisser vide pour ne pas changer)',
                'required' => false,
                'constraints' => [
                    new Length(['min' => 8, 'max' => 255, 'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères']),
                ],
                'attr' => ['class' => 'form-control', 'placeholder' => 'Laissez vide pour ne pas changer']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
