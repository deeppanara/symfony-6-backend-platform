<?php
/*
 * *************************************************************************
 * Copyright (C) 2023, Inc - All Rights Reserved
 * This file is part of the Dom bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Deep Panara <panaradeep@gmail.com>
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Form/Type/Console/UserGroupType.php
 *
 */

namespace Platform\Form\Type\Console;

use Platform\DTO\UserGroup\UserGroup;
use Platform\Entity\Role;
use Platform\Form\DataTransformer\RoleTransformer;
use Platform\Form\Type\FormTypeLabelInterface;
use Platform\Form\Type\Traits\AddBasicFieldToForm;
use Platform\Resource\RoleResource;
use Platform\Security\RolesService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

/*
 *
 * @psalm-suppress MissingTemplateParam
 */

/**
 *
 */
class UserGroupType extends AbstractType
{
    use AddBasicFieldToForm;

    /**
     * Base form fields
     *
     * @var array<int, array<int, mixed>>
     */
    private static array $formFields = [
        [
            'name',
            Type\TextType::class,
            [
                FormTypeLabelInterface::LABEL => 'Group name',
                FormTypeLabelInterface::REQUIRED => true,
                FormTypeLabelInterface::EMPTY_DATA => '',
            ],
        ],
    ];

    public function __construct(
        private readonly RolesService $rolesService,
        private readonly RoleResource $roleResource,
        private readonly RoleTransformer $roleTransformer,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws Throwable
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addBasicFieldToForm($builder, self::$formFields);

        $builder
            ->add(
                'role',
                Type\ChoiceType::class,
                [
                    FormTypeLabelInterface::LABEL => 'Role',
                    FormTypeLabelInterface::CHOICES => $this->getRoleChoices(),
                    FormTypeLabelInterface::REQUIRED => true,
                ],
            );

        $builder->get('role')->addModelTransformer($this->roleTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
    }

    /**
     * Method to get choices array for user groups.
     *
     * @return array<string, string>
     *
     * @throws Throwable
     */
    public function getRoleChoices(): array
    {
        // Initialize output
        $choices = [];

        $iterator = function (Role $role) use (&$choices): void {
            $choices[$this->rolesService->getRoleLabel($role->getId())] = $role->getId();
        };

        array_map($iterator, $this->roleResource->find());

        return $choices;
    }
}
