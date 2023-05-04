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
 * /tests/Integration/AutoMapper/TestRestRequestMapper.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\AutoMapper\src;

use Platform\AutoMapper\RestRequestMapper;
use function str_rot13;

/**
 * Class TestRestRequestMapper
 *
 * @package App\Tests\Integration\AutoMapper\src
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class TestRestRequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'someProperty',
        'someTransformProperty',
    ];

    public function transformSomeTransformProperty(string $input): string
    {
        return str_rot13($input);
    }
}
