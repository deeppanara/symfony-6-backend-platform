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
 * /src/Utils/LoginLogger.php
 *
 */

namespace Platform\Utils;

use BadMethodCallException;
use DeviceDetector\DeviceDetector;
use Platform\Entity\LogLogin;
use Platform\Entity\User;
use Platform\Resource\LogLoginResource;
use Platform\Utils\Interfaces\LoginLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;


/**
 *
 */
class LoginLogger implements LoginLoggerInterface
{
    private readonly DeviceDetector $deviceDetector;
    private ?User $user = null;

    public function __construct(
        private readonly LogLoginResource $logLoginResource,
        private readonly RequestStack $requestStack,
    ) {
        $this->deviceDetector = new DeviceDetector();
    }

    public function setUser(?User $user = null): self
    {
        $this->user = $user;

        return $this;
    }

    public function process(string $type): void
    {
        // Get current request
        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new BadMethodCallException('Could not get request from current request stack');
        }

        // Parse user agent data with device detector
        $this->deviceDetector->setUserAgent($request->headers->get('User-Agent', ''));
        $this->deviceDetector->parse();

        // Create entry
        $this->createEntry($type, $request);
    }

    /**
     * Method to create new login entry and store it to database.
     *
     * @throws Throwable
     */
    private function createEntry(string $type, Request $request): void
    {
        $entry = new LogLogin($type, $request, $this->deviceDetector, $this->user);

        // And store entry to database
        $this->logLoginResource->save($entry, true);
    }
}
