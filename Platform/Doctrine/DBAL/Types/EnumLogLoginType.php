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
 * /src/Doctrine/DBAL/Types/EnumLogLoginType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;


/**
 *
 */
class EnumLogLoginType extends EnumType
{
    final public const TYPE_FAILURE = 'failure';
    final public const TYPE_SUCCESS = 'success';

    protected static string $name = Types::ENUM_LOG_LOGIN;

    /**
     * @var array<int, string>
     */
    protected static array $values = [
        self::TYPE_FAILURE,
        self::TYPE_SUCCESS,
    ];
}
