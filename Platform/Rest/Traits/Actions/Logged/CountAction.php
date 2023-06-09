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
 * /src/Rest/Traits/Actions/Logged/CountAction.php
 *
 */

namespace Platform\Rest\Traits\Actions\Logged;

use Platform\Enum\Role;
use Platform\Rest\Traits\Methods\CountMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/**
 * Trait CountAction
 *
 * Trait to add 'countAction' for REST controllers for 'ROLE_LOGGED' users.
 *
 * @see \Platform\Rest\Traits\Methods\CountMethod for detailed documents.
 */
trait CountAction
{
    use CountMethod;

    /**
     * @throws Throwable
     */
    #[Route(
        path: '/count',
        methods: [Request::METHOD_GET],
    )]
    #[IsGranted(Role::LOGGED->value)]
    public function countAction(Request $request): Response
    {
        return $this->countMethod($request);
    }
}
