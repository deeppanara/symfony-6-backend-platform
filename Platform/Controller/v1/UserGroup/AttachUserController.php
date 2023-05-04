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
 * /src/Controller/v1/UserGroup/AttachUserController.php
 *
 */

namespace Platform\Controller\v1\UserGroup;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Enum\Role;
use Platform\Resource\UserGroupResource;
use Platform\Resource\UserResource;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;


/**
 *
 */
#[AsController]
#[OpenApi\Attributes\Tag(name: 'UserGroup Management')]
class AttachUserController
{
    public function __construct(
        private readonly UserResource $userResource,
        private readonly UserGroupResource $userGroupResource,
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * Endpoint action to attach specified user to specified user group.
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
     * @OA\Parameter(
     *      name="userGroupId",
     *      in="path",
     *      required=true,
     *      description="User Group GUID",
     *      @OA\Schema(
     *          type="string",
     *          default="User Group GUID",
     *      )
     *  )
     * @OA\Parameter(
     *      name="userId",
     *      in="path",
     *      required=true,
     *      description="User GUID",
     *      @OA\Schema(
     *          type="string",
     *          default="User GUID",
     *      )
     *  )
     * @OA\Response(
     *      response=200,
     *      description="List of user group users - specified user already exists on this group",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              ref=@Model(
     *                  type=\Platform\Entity\User::class,
     *                  groups={"User"},
     *              ),
     *          ),
     *      ),
     *  )
     * @OA\Response(
     *      response=201,
     *      description="List of user group users - specified user has been attached to this group",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              ref=@Model(
     *                  type=\Platform\Entity\User::class,
     *                  groups={"User"},
     *              ),
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
     *      response=403,
     *      description="Access denied",
     *  )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/v1/user_group/{userGroup}/user/{user}',
        requirements: [
            'userGroup' => Requirement::UUID_V1,
            'user' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_POST],
    )]
    #[IsGranted(Role::ROOT->value)]
    public function __invoke(UserGroup $userGroup, User $user): JsonResponse
    {
        $status = $userGroup->getUsers()->contains($user) ? 200 : 201;

        $this->userGroupResource->save($userGroup->addUser($user), false);
        $this->userResource->save($user, true, true);

        $groups = [
            'groups' => [
                User::SET_USER_BASIC,
            ],
        ];

        return new JsonResponse(
            $this->serializer->serialize($userGroup->getUsers()->getValues(), 'json', $groups),
            $status,
            json: true,
        );
    }
}
