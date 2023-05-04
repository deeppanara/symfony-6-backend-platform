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
 * /src/Rest/Traits/RestMethodProcessCriteria.php
 *
 */

namespace Platform\Rest\Traits\Methods;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait RestMethodProcessCriteria
 */
trait RestMethodProcessCriteria
{
    /**
     * @param array<int|string, string|array<mixed>> $criteria
     */
    public function processCriteria(array &$criteria, Request $request, string $method): void
    {
    }
}
