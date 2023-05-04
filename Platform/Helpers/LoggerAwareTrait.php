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
 * /src/Helpers/LoggerAwareTrait.php
 *
 */

namespace Platform\Helpers;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Trait LoggerAwareTrait
 *
 * NOTE: Do not use this in your services, just inject `LoggerInterface` to
 *       service where you need it. This trait is just for quick debug purposes
 *       and nothing else.
 */
trait LoggerAwareTrait
{
    protected ?LoggerInterface $logger = null;

    #[Required]
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}
