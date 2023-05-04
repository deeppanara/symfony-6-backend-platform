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
 * /tests/Integration/Controller/v1/ApiKey/ApiKeyControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\ApiKey;

use App\Tests\Integration\TestCase\RestIntegrationControllerTestCase;
use Platform\Controller\v1\ApiKey\ApiKeyController;
use Platform\Resource\ApiKeyResource;

/**
 * Class ApiKeyControllerTest
 *
 * @package App\Tests\Integration\Controller\v1
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method ApiKeyController getController()
 */
class ApiKeyControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var class-string
     */
    protected string $controllerClass = ApiKeyController::class;

    /**
     * @var class-string
     */
    protected string $resourceClass = ApiKeyResource::class;
}
