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
 * @date     01/05/23, 12:10 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Command/HelperConfigure.php
 *
 */

namespace Platform\Command;

use Closure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use function array_key_exists;
use function array_map;


/**
 *
 */
class HelperConfigure
{
    /**
     * @param array<int, array<string, int|string>> $parameters
     */
    public static function configure(Command $command, array $parameters): void
    {
        // Configure command
        $command->setDefinition(new InputDefinition(array_map(self::getParameterIterator(), $parameters)));
    }

    private static function getParameterIterator(): Closure
    {
        return static fn (array $input): InputOption => new InputOption(
            (string)$input['name'],
            array_key_exists('shortcut', $input) ? (string)$input['shortcut'] : null,
            array_key_exists('mode', $input) ? (int)$input['mode'] : InputOption::VALUE_OPTIONAL,
            array_key_exists('description', $input) ? (string)$input['description'] : '',
            array_key_exists('default', $input) ? (string)$input['default'] : null,
        );
    }
}
