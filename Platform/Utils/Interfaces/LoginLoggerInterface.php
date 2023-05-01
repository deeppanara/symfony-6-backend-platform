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
 * @date     01/05/23, 12:19 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Utils/Interfaces/LoginLoggerInterface.php
 *
 */

namespace Platform\Utils\Interfaces;

use Platform\Entity\User;
use Platform\Resource\LogLoginResource;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;

/**
 * Interface LoginLoggerInterface
 */
interface LoginLoggerInterface
{
    public function __construct(LogLoginResource $logLoginFailureResource, RequestStack $requestStack);

    /**
     * Setter for User object (Entity).
     */
    public function setUser(?User $user = null): self;

    /**
     * Method to handle login event.
     *
     * @throws Throwable
     */
    public function process(string $type): void;
}
