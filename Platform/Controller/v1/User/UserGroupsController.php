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
 * /src/Controller/v1/User/UserGroupsController.php
 *
 */

namespace Platform\Controller\v1\User;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use OpenApi\Attributes as OAA;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;


/**
 *
 */
#[AsController]
#[OAA\Tag(name: 'UserGroup Management')]
class UserGroupsController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * Endpoint action to fetch specified user user groups.
     *
     * @OA\Tag(name="User Management")
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
     *      description="User groups",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              ref=@Model(
     *                  type=\Platform\Entity\UserGroup::class,
     *                  groups={"UserGroup", "UserGroup.role"},
     *              ),
     *          ),
     *      ),
     *  )
     * @OA\Response(
     *      response=401,
     *      description="Unauthorized",
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
     *  @OA\Response(
     *      response=403,
     *      description="Access denied",
     *      @OA\Schema(
     *          type="object",
     *          example={
     *              "Access denied": "{code: 403, message: 'Access denied'}",
     *          },
     *          @OA\Property(property="code", type="integer", description="Error code"),
     *          @OA\Property(property="message", type="string", description="Error description"),
     *      ),
     *  )
     */
    #[Route(
        path: '/v1/user/{user}/groups',
        requirements: [
            'user' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(new Expression('is_granted("IS_USER_HIMSELF", object) or "ROLE_ROOT" in role_names'), 'user')]
    public function __invoke(User $user): JsonResponse
    {
        $groups = [
            'groups' => [
                UserGroup::SET_USER_GROUP_BASIC,
            ],
        ];

        return new JsonResponse(
            $this->serializer->serialize($user->getUserGroups()->getValues(), 'json', $groups),
            json: true
        );
    }
}
