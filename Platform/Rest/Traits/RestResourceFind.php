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
 * /src/Rest/Traits/RestResourceFind.php
 *
 */

namespace Platform\Rest\Traits;

use Platform\Entity\Interfaces\EntityInterface;

/**
 * Trait RestResourceFind
 *
 * @SuppressWarnings("unused")
 */
trait RestResourceFind
{
    /**
     * Before lifecycle method for find method.
     *
     * @param mixed[] $criteria
     * @param mixed[] $orderBy
     * @param mixed[] $search
     */
    public function beforeFind(array &$criteria, array &$orderBy, ?int &$limit, ?int &$offset, array &$search): void
    {
    }

    /**
     * After lifecycle method for find method.
     *
     * Notes: If you make changes to entities in this lifecycle method by default it will be saved on end of current
     *          request. To prevent this you need to clone each entity and use those.
     *
     * @param mixed[] $criteria
     * @param mixed[] $orderBy
     * @param mixed[] $search
     * @param EntityInterface[] $entities
     */
    public function afterFind(
        array &$criteria,
        array &$orderBy,
        ?int &$limit,
        ?int &$offset,
        array &$search,
        array &$entities
    ): void {
    }
}
