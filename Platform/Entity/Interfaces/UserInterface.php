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
 * /src/Entity/Interfaces/UserInterface.php
 *
 */

namespace Platform\Entity\Interfaces;

/**
 * Interface UserInterface
 */
interface UserInterface
{
    public function getId(): string;
    public function getUsername(): string;
    public function getEmail(): string;
}
