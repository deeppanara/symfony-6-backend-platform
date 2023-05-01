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
 * /tests/Integration/Rest/src\AbstractController.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Rest\src;

use Platform\Rest\Controller;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class AbstractController
 *
 * @package App\Tests\Integration\Rest\src
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
abstract class AbstractController extends Controller
{
    public function __construct(
        RestResourceInterface $resource,
    ) {
        parent::__construct($resource);
    }
}
