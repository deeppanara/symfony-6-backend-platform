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
 * /src/Rest/Traits/RestResourceIds.php
 *
 */

namespace Platform\Rest\Traits;

/**
 * Trait RestResourceIds
 *
 * @SuppressWarnings("unused")
 */
trait RestResourceIds
{
    /**
     * Before lifecycle method for ids method.
     *
     * @param mixed[] $criteria
     * @param mixed[] $search
     */
    public function beforeIds(array &$criteria, array &$search): void
    {
    }

    /**
     * Before lifecycle method for ids method.
     *
     * @param mixed[] $criteria
     * @param mixed[] $search
     * @param string[] $ids
     */
    public function afterIds(array &$criteria, array &$search, array &$ids): void
    {
    }
}
