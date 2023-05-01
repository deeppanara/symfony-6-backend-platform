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
 * /tests/E2E/Rest/src/Controller/ControllerForLifeCycleTests.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Rest\src\Controller;

use App\Rest\Traits\Methods;
use App\Tests\E2E\Rest\src\Resource\ResourceForLifeCycleTests;
use Platform\Enum\Role;
use Platform\Rest\Controller;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Throwable;

/**
 * Class ControllerForLifeCycleTests
 *
 * @package App\Tests\E2E\Rest\src\Controller
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
#[Route(
    path: '/test_lifecycle_behaviour',
)]
#[AsController]
#[AutoconfigureTag('app.rest.controller')]
class ControllerForLifeCycleTests extends Controller
{
    // Traits
    use \Platform\Rest\Traits\Methods\FindOneMethod;

    public function __construct(
        ResourceForLifeCycleTests $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{role}',
        requirements: [
            'role' => new EnumRequirement(Role::class),
        ],
        methods: [Request::METHOD_GET],
    )]
    public function findOneAction(Request $request, string $role): Response
    {
        return $this->findOneMethod($request, $role);
    }
}
