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
 * /src/Controller/v1/UserGroup/UserGroupController.php
 *
 */

namespace Platform\Controller\v1\UserGroup;

use Platform\Rest\Traits\Actions;
use Platform\DTO\UserGroup\UserGroupCreate;
use Platform\DTO\UserGroup\UserGroupPatch;
use Platform\DTO\UserGroup\UserGroupUpdate;
use Platform\Resource\UserGroupResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/*
 *
 * @OA\Tag(name="UserGroup Management")
 *
 * @method UserGroupResource getResource()
 */

/**
 *
 */
#[AsController]
#[\OpenApi\Attributes\Tag(name: 'UserGroup Management')]
#[Route(
    path: '/v1/user_group',
)]
//#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class UserGroupController extends Controller
{

    use \Platform\Rest\Traits\Actions\Anon\CountAction;
    use \Platform\Rest\Traits\Actions\Anon\FindAction;
    use \Platform\Rest\Traits\Actions\Anon\FindOneAction;
    use \Platform\Rest\Traits\Actions\Anon\IdsAction;
    use \Platform\Rest\Traits\Actions\Anon\CreateAction;
    use \Platform\Rest\Traits\Actions\Anon\PatchAction;
    use \Platform\Rest\Traits\Actions\Anon\UpdateAction;
    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => UserGroupCreate::class,
        Controller::METHOD_UPDATE => UserGroupUpdate::class,
        Controller::METHOD_PATCH => UserGroupPatch::class,
    ];

    public function __construct(
        UserGroupResource $resource,
    ) {
        parent::__construct($resource);
    }
}
