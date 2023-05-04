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
 * /src/Utils/HealthzService.php
 *
 */

namespace Platform\Utils;

use Platform\Entity\Healthz;
use Platform\Repository\HealthzRepository;
use Throwable;


/**
 *
 */
class HealthzService
{
    public function __construct(
        private readonly HealthzRepository $repository,
    ) {
    }

    /**
     * Method to check that "all" is ok within our application. This will try
     * to do following:
     *  1) Remove data from database
     *  2) Create data to database
     *  3) Read data from database
     *
     * These steps should make sure that at least application database is
     * working as expected.
     *
     * @throws Throwable
     */
    public function check(): ?Healthz
    {
        $this->repository->cleanup();
        $this->repository->create();

        return $this->repository->read();
    }
}
