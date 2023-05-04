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
 * /src/Entity/Interfaces/EntityInterface.php
 *
 */

namespace Platform\Entity\Interfaces;

use DateTimeImmutable;

/**
 * Interface EntityInterface
 */
interface EntityInterface
{
    public function getId(): string;
    public function getCreatedAt(): ?DateTimeImmutable;
}
