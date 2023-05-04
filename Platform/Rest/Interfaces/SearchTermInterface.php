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
 * /src/Rest/Interfaces/SearchTermInterface.php
 *
 */

namespace Platform\Rest\Interfaces;

/**
 * Interface SearchTermInterface
 */
interface SearchTermInterface
{
    // @codeCoverageIgnoreStart
    // Used OPERAND constants
    public const OPERAND_OR = 'or';
    public const OPERAND_AND = 'and';

    // Used MODE constants
    public const MODE_STARTS_WITH = 1;
    public const MODE_ENDS_WITH = 2;
    public const MODE_FULL = 3;
    // @codeCoverageIgnoreEnd

    /**
     * Static method to get search term criteria for specified columns and search terms with specified operand and mode.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @param string|array<int, string> $column  search column(s), could be a string or an array of strings
     * @param string|array<int, string> $search  search term(s), could be a string or an array of strings
     * @param string|null               $operand Used operand with multiple search terms. See OPERAND_* constants.
     *                                           Defaults to self::OPERAND_OR
     * @param int|null                  $mode    Used mode on LIKE search. See MODE_* constants. Defaults to
     *                                           self::MODE_FULL
     *
     * @return array<string, array<string, array<string, string>>>|null
     */
    public static function getCriteria(
        array | string $column,
        array | string $search,
        ?string $operand = null,
        ?int $mode = null,
    ): ?array;
}
