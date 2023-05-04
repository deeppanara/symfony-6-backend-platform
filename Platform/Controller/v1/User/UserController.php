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
 * /src/Controller/v1/User/UserController.php
 *
 */

namespace Platform\Controller\v1\User;

use Platform\Rest\Traits\Actions;
use Platform\DTO\User\UserCreate;
use Platform\DTO\User\UserPatch;
use Platform\DTO\User\UserUpdate;
use Platform\Resource\UserResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

/**
 *
 */
#[AsController]
#[Route(
    path: '/v1/user',
)]
#[OA\Tag(name: 'User Management')]
//#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class UserController extends Controller
{
    use Actions\Anon\CountAction;
    use Actions\Anon\FindAction;
    use Actions\Anon\FindOneAction;
    use Actions\Anon\IdsAction;
    use Actions\Anon\CreateAction;
    use Actions\Anon\PatchAction;
    use Actions\Anon\UpdateAction;

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => UserCreate::class,
        Controller::METHOD_UPDATE => UserUpdate::class,
        Controller::METHOD_PATCH => UserPatch::class,
    ];

    public function __construct(
        UserResource $resource,
    ) {
        parent::__construct($resource);
    }
}
