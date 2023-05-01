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
 * @date     01/05/23, 12:36 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Controller/v1/Role/RoleController.php
 *
 */

namespace Platform\Controller\v1\Role;

use Platform\Rest\Traits\Actions;
use Platform\Resource\RoleResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/*
 *
 * @OA\Tag(name="Role Management")
 *
 * @method RoleResource getResource()
 */

/**
 *
 */
#[AsController]
#[Route(
    path: '/v1/role',
)]
//#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class RoleController extends Controller
{
    use \Platform\Rest\Traits\Actions\Anon\CountAction;
    use \Platform\Rest\Traits\Actions\Anon\FindAction;
    use \Platform\Rest\Traits\Actions\Anon\IdsAction;

    public function __construct(
        RoleResource $resource,
    ) {
        parent::__construct($resource);
    }
}
