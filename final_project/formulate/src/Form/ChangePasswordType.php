<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'first_options'   => [
                    'label'       => 'New Password',
                    'attr'        => ['placeholder' => '••••••••'],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min' => 6]),
                    ],
                ],
                'second_options'  => [
                    'label'       => 'Confirm Password',
                    'attr'        => ['placeholder' => '••••••••'],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped'          => false,
            ]);
    }
}
