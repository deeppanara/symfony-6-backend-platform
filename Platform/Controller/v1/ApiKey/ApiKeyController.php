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
 * /src/Controller/v1/ApiKey/ApiKeyController.php
 *
 */

namespace Platform\Controller\v1\ApiKey;

use Platform\Rest\Traits\Actions;
use Platform\DTO\ApiKey\ApiKeyCreate;
use Platform\DTO\ApiKey\ApiKeyPatch;
use Platform\DTO\ApiKey\ApiKeyUpdate;
use Platform\Resource\ApiKeyResource;
use Platform\Rest\Controller;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/*
 *
 * @OA\Tag(name="ApiKey Management")
 *
 * @method ApiKeyResource getResource()
 */

/**
 *
 */
#[AsController]
#[Route(
    path: '/v1/api_key',
)]
#[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
class ApiKeyController extends Controller
{
    use \Platform\Rest\Traits\Actions\Root\CountAction;
    use \Platform\Rest\Traits\Actions\Root\FindAction;
    use \Platform\Rest\Traits\Actions\Root\FindOneAction;
    use \Platform\Rest\Traits\Actions\Root\IdsAction;
    use \Platform\Rest\Traits\Actions\Root\CreateAction;
    use \Platform\Rest\Traits\Actions\Root\DeleteAction;
    use \Platform\Rest\Traits\Actions\Root\PatchAction;
    use \Platform\Rest\Traits\Actions\Root\UpdateAction;

    /**
     * @var array<string, string>
     */
    protected static array $dtoClasses = [
        Controller::METHOD_CREATE => ApiKeyCreate::class,
        Controller::METHOD_UPDATE => ApiKeyUpdate::class,
        Controller::METHOD_PATCH => ApiKeyPatch::class,
    ];

    public function __construct(
        ApiKeyResource $resource,
    ) {
        parent::__construct($resource);
    }
}
