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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Exception/interfaces/ClientErrorInterface.php
 *
 */

namespace Platform\Exception\interfaces;

/*
 *
 * If you want to expose _your_ exception class message as-is on `prod`
 * environment that should implement either this interface or
 * `Symfony\Component\HttpKernel\HttpKernelInterface` to get that message to
 * frontend side - otherwise you will just get `Internal server error.` message
 * with HTTP status 500.
 *
 * If your exception is not returning `code` properly, note that you will get
 * that HTTP status 500 on those - so it's _your_ responsibility to get
 * "proper" status code in your exception class.
 */

/**
 *
 */
interface ClientErrorInterface
{
    /**
     * Method to get client response status code.
     */
    public function getStatusCode(): int;
}
