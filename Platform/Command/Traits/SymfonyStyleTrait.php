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
 * /src/Command/Traits/SymfonyStyleTrait.php
 *
 */

namespace Platform\Command\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Trait SymfonyStyleTrait
 */
trait SymfonyStyleTrait
{
    /**
     * Method to get SymfonyStyle object for console commands.
     */
    protected function getSymfonyStyle(
        InputInterface $input,
        OutputInterface $output,
        ?bool $clearScreen = null,
    ): SymfonyStyle {
        $clearScreen ??= true;

        $io = new SymfonyStyle($input, $output);

        if ($clearScreen) {
            $io->write("\033\143");
        }

        return $io;
    }
}
