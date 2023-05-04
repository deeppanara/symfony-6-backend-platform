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
 * /src/Controller/v1/UserGroup/UsersController.php
 *
 */

namespace Platform\Controller\v1\UserGroup;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Enum\Role;
use Platform\Resource\UserResource;
use Platform\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;


/**
 *
 */
#[AsController]
#[OpenApi\Attributes\Tag(name: 'UserGroup Management')]
class UsersController
{
    public function __construct(
        private readonly UserResource $userResource,
        private readonly ResponseHandler $responseHandler,
    ) {
    }

    /**
     * Endpoint action to list specified user group users.
     *
     * @OA\Tag(name="UserGroup Management")
     * @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      required=true,
     *      description="Authorization header",
     *      @OA\Schema(
     *          type="string",
     *          default="Bearer _your_jwt_here_",
     *      )
     *  )
     * @OA\Response(
     *      response=200,
     *      description="User group users",
     * @OA\Schema(
     *          ref=@Model(
     *              type=User::class,
     *              groups={"User", "User.userGroups", "User.roles", "UserGroup", "UserGroup.role"},
     *          ),
     *      ),
     *  )
     * @OA\Response(
     *      response=401,
     *      description="Invalid token",
     *      @OA\Schema(
     *          example={
     *              "Token not found": "{code: 401, message: 'JWT Token not found'}",
     *              "Expired token": "{code: 401, message: 'Expired JWT Token'}",
     *          },
     *      ),
     *  )
     * @OA\Response(
     *      response=404,
     *      description="User Group not found",
     *  )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/v1/user_group/{userGroup}/users',
        requirements: [
            'userGroup' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(Role::ROOT->value)]
    public function __invoke(Request $request, UserGroup $userGroup): Response
    {
        return $this->responseHandler
            ->createResponse($request, $this->userResource->getUsersForGroup($userGroup), $this->userResource);
    }
}
