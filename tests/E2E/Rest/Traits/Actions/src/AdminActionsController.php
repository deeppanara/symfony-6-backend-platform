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
 * /tests/E2E/Rest/Traits/Actions/src/AdminActionsController.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Rest\Traits\Actions\src;

use Platform\DTO\User\UserCreate;
use Platform\DTO\User\UserPatch;
use Platform\DTO\User\UserUpdate;
use Platform\Resource\UserResource;
use Platform\Rest\Controller;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class AdminActionsController
 *
 * @package App\Tests\E2E\Rest\Traits\Actions\src
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
#[AsController]
#[AutoconfigureTag('app.rest.controller')]
#[Route(
    path: '/test_admin_actions',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class AdminActionsController extends Controller
{
    use \Platform\Rest\Traits\Actions\Admin\CountAction;
    use \Platform\Rest\Traits\Actions\Admin\CreateAction;
    use \Platform\Rest\Traits\Actions\Admin\DeleteAction;
    use \Platform\Rest\Traits\Actions\Admin\FindAction;
    use \Platform\Rest\Traits\Actions\Admin\FindOneAction;
    use \Platform\Rest\Traits\Actions\Admin\IdsAction;
    use \Platform\Rest\Traits\Actions\Admin\UpdateAction;
    use \Platform\Rest\Traits\Actions\Admin\PatchAction;

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
