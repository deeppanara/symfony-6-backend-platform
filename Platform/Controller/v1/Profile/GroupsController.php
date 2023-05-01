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
 * @date     01/05/23, 12:34 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Controller/v1/Profile/GroupsController.php
 *
 */

namespace Platform\Controller\v1\Profile;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;


/**
 *
 */
#[AsController]
class GroupsController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    /**
     * Endpoint action to get current user user groups.
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
     *      description="User groups",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              ref=@Model(
     *                  type=\Platform\Entity\UserGroup::class,
     *                  groups={"set.UserProfileGroups"},
     *              ),
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
     * @OA\Response(
     *      response=403,
     *      description="Access denied",
     *      @OA\Schema(
     *          type="403",
     *          example={
     *              "Access denied": "{code: 403, message: 'Access denied'}",
     *          },
     *          @OA\Property(property="code", type="integer", description="Error code"),
     *          @OA\Property(property="message", type="string", description="Error description"),
     *      ),
     *  )
     * @OA\Tag(name="Profile")
     */
    #[Route(
        path: '/v1/profile/groups',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function __invoke(User $loggedInUser): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $loggedInUser->getUserGroups()->toArray(),
                'json',
                [
                    'groups' => UserGroup::SET_USER_PROFILE_GROUPS,
                ],
            ),
            json: true,
        );
    }
}
