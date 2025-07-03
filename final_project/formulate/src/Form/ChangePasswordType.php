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
                        new Assert\NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Assert\Length([
                            'min'        => 8,
                            'minMessage' => 'Your password must be at least {{ limit }} characters long',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/[A-Z]/',
                            'message' => 'Your password must contain at least one uppercase letter',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/[a-z]/',
                            'message' => 'Your password must contain at least one lowercase letter',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/\d/',
                            'message' => 'Your password must contain at least one number',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/[\W]/',
                            'message' => 'Your password must contain at least one special character',
                        ]),
                    ],
                ],
                'second_options'  => [
                    'label' => 'Confirm Password',
                    'attr'  => ['placeholder' => '••••••••'],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped'          => false,
            ]);

    }
}
