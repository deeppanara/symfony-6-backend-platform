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
 * @date     01/05/23, 12:13 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Doctrine/DBAL/Types/EnumLanguageType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;

use BackedEnum;
use Platform\Enum\Interfaces\DatabaseEnumInterface;
use Platform\Enum\Language;


/**
 *
 */
class EnumLanguageType extends RealEnumType
{
    protected static string $name = Types::ENUM_LANGUAGE;

    /**
     * @psalm-var class-string<DatabaseEnumInterface&BackedEnum>
     */
    protected static string $enum = Language::class;
}
