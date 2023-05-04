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
 * /src/Controller/v1/Role/FindOneRoleController.php
 *
 */

namespace Platform\Controller\v1\Role;

use Platform\Rest\Traits\Methods;
use Platform\Enum\Role;
use Platform\Resource\RoleResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/*
 *
 * @OA\Tag(name="Role Management")
 */

/**
 *
 */
#[AsController]
#[\OpenApi\Attributes\Tag(name: 'Role Management')]
class FindOneRoleController extends Controller
{
    use \Platform\Rest\Traits\Methods\FindOneMethod;

    public function __construct(
        RoleResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/v1/role/{role}',
        requirements: [
            'role' => new EnumRequirement(Role::class),
        ],
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(Role::ADMIN->value)]
    public function __invoke(Request $request, string $role): Response
    {
        return $this->findOneMethod($request, $role);
    }
}
