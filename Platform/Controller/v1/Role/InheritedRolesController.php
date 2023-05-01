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
 * /src/Controller/v1/Role/InheritedRolesController.php
 *
 */

namespace Platform\Controller\v1\Role;

use OpenApi\Annotations as OA;
use Platform\Entity\Role;
use Platform\Enum\Role as RoleEnum;
use Platform\Security\RolesService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/*
 *
 * @OA\Tag(name="Role Management")
 */

/**
 *
 */
#[AsController]
class InheritedRolesController
{
    public function __construct(
        private readonly RolesService $rolesService,
    ) {
    }

    /**
     * Endpoint action to return all inherited roles as an array for specified
     * Role.
     *
     * @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      required=true,
     *      description="Authorization header",
     *      @OA\Schema(
     *          type="string",
     *          default="Bearer _your_jwt_here_",
     *      ),
     *  )
     * @OA\Response(
     *      response=200,
     *      description="Inherited roles",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              type="string",
     *          ),
     *      ),
     *  )
     * @OA\Response(
     *      response=401,
     *      description="Invalid token",
     *      @OA\Schema(
     *          type="object",
     *          example={
     *              "Token not found": "{code: 401, message: 'JWT Token not found'}",
     *              "Expired token": "{code: 401, message: 'Expired JWT Token'}",
     *          },
     *          @OA\Property(property="code", type="integer", description="Error code"),
     *          @OA\Property(property="message", type="string", description="Error description"),
     *      ),
     *  )
     */
    #[Route(
        path: '/v1/role/{role}/inherited',
        requirements: [
            'role' => new EnumRequirement(RoleEnum::class),
        ],
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(RoleEnum::ADMIN->value)]
    public function __invoke(Role $role): JsonResponse
    {
        return new JsonResponse($this->rolesService->getInheritedRoles([$role->getId()]));
    }
}
