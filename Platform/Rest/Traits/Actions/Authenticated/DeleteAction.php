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
 * /src/Rest/Traits/Actions/Authenticated/DeleteAction.php
 *
 */

namespace Platform\Rest\Traits\Actions\Authenticated;

use Platform\Rest\Traits\Methods\DeleteMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * Trait DeleteAction
 *
 * Trait to add 'deleteAction' for REST controllers for authenticated users.
 *
 * @see \Platform\Rest\Traits\Methods\DeleteMethod for detailed documents.
 */
trait DeleteAction
{
    use DeleteMethod;

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/{id}',
        requirements: [
            'id' => Requirement::UUID_V1,
        ],
        methods: [Request::METHOD_DELETE],
    )]
    #[IsGranted(AuthenticatedVoter::IS_AUTHENTICATED_FULLY)]
    public function deleteAction(Request $request, string $id): Response
    {
        return $this->deleteMethod($request, $id);
    }
}
