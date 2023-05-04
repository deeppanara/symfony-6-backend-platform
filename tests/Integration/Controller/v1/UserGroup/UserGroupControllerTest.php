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
 * /tests/Integration/Controller/v1/UserGroup/UserGroupControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\UserGroup;

use App\Tests\Integration\TestCase\RestIntegrationControllerTestCase;
use Platform\Controller\v1\UserGroup\UserGroupController;
use Platform\Resource\UserGroupResource;

/**
 * Class UserGroupControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method \Platform\Controller\v1\UserGroup\UserGroupController getController()
 */
class UserGroupControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var class-string
     */
    protected string $controllerClass = UserGroupController::class;

    /**
     * @var class-string
     */
    protected string $resourceClass = UserGroupResource::class;
}
