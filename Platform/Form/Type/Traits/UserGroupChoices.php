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
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Form/Type/Traits/UserGroupChoices.php
 *
 */

namespace Platform\Form\Type\Traits;

use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
use Throwable;
use function array_map;

/**
 * Trait UserGroupChoices
 *
 * @property UserGroupResource $userGroupResource
 */
trait UserGroupChoices
{
    /**
     * Method to create choices array for user groups.
     *
     * @return array<string, string>
     *
     * @throws Throwable
     */
    protected function getUserGroupChoices(): array
    {
        // Initialize output
        $choices = [];

        /**
         * Lambda function to iterate all user groups and to create necessary
         * choices array.
         */
        $iterator = static function (UserGroup $userGroup) use (&$choices): void {
            $name = $userGroup->getName() . ' [' . $userGroup->getRole()->getId() . ']';

            $choices[$name] = $userGroup->getId();
        };

        array_map($iterator, $this->userGroupResource->find());

        return $choices;
    }
}
