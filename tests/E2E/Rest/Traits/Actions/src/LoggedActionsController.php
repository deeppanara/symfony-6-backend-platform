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
 * /tests/E2E/Rest/Traits/Actions/src/LoggedActionsController.php
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
 * Class LoggedActionsController
 *
 * @package App\Tests\E2E\Rest\Traits\Actions\src
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
#[AsController]
#[AutoconfigureTag('app.rest.controller')]
#[Route(
    path: '/test_logged_actions',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class LoggedActionsController extends Controller
{
    use \Platform\Rest\Traits\Actions\Logged\CountAction;
    use \Platform\Rest\Traits\Actions\Logged\CreateAction;
    use \Platform\Rest\Traits\Actions\Logged\DeleteAction;
    use \Platform\Rest\Traits\Actions\Logged\FindAction;
    use \Platform\Rest\Traits\Actions\Logged\FindOneAction;
    use \Platform\Rest\Traits\Actions\Logged\IdsAction;
    use \Platform\Rest\Traits\Actions\Logged\UpdateAction;
    use \Platform\Rest\Traits\Actions\Logged\PatchAction;

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
