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
 * /src/Rest/Traits/Actions/RestActionBase.php
 *
 */

namespace Platform\Rest\Traits\Actions;

use Platform\Rest\Interfaces\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait RestActionBase
 */
trait RestActionBase
{
    /**
     * @param array<int, string> $allowedHttpMethods
     */
    public function getResourceForMethod(Request $request, array $allowedHttpMethods): RestResourceInterface
    {
        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Get current resource service
        return $this->getResource();
    }
}
