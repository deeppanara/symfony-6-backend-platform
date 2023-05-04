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
 * /src/Controller/v1/User/DeleteUserController.php
 *
 */

namespace Platform\Controller\v1\User;

use Platform\Rest\Traits\Methods;
use Platform\Entity\User;
use Platform\Enum\Role;
use Platform\Resource\UserResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use OpenApi\Attributes as OA;
use Throwable;

/*
 *
 * @OA\Tag(name="User Management")
 */

/**
 *
 */
#[AsController]
#[OA\Tag(name: 'User Management')]
class DeleteUserController extends Controller
{
    use \Platform\Rest\Traits\Methods\DeleteMethod;

    public function __construct(
        UserResource $resource,
    ) {
        parent::__construct($resource);
    }

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/v1/user/{user}',
        requirements: [
            'requestUser' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_DELETE],
    )]
    #[IsGranted(Role::ROOT->value)]
    public function __invoke(Request $request, User $user, User $loggedInUser): Response
    {
        if ($loggedInUser === $user) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'You cannot remove yourself...');
        }

        return $this->deleteMethod($request, $user->getId());
    }
}
