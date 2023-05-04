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
 * /src/Enum/Role.php
 *
 */

namespace Platform\Enum;

/**
 * Enum Role
 */
enum Role: string
{
    case LOGGED = 'ROLE_LOGGED';
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
    case ROOT = 'ROLE_ROOT';
    case API = 'ROLE_API';

    public function label(): string
    {
        return match ($this) {
            self::LOGGED => 'Logged in users',
            self::USER => 'Normal users',
            self::ADMIN => 'Admin users',
            self::ROOT => 'Root users',
            self::API => 'API users',
        };
    }
}
