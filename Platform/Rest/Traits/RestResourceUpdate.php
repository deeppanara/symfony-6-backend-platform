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
 * /src/Rest/Traits/RestResourceUpdate.php
 *
 */

namespace Platform\Rest\Traits;

use Platform\DTO\RestDtoInterface;
use Platform\Entity\Interfaces\EntityInterface;

/**
 * Trait RestResourceUpdate
 *
 * @SuppressWarnings("unused")
 */
trait RestResourceUpdate
{
    /**
     * Before lifecycle method for update method.
     */
    public function beforeUpdate(string &$id, RestDtoInterface $restDto, EntityInterface $entity): void
    {
    }

    /**
     * After lifecycle method for update method.
     *
     * Notes: If you make changes to entity in this lifecycle method by default it will be saved on end of current
     *          request. To prevent this you need to detach current entity from entity manager.
     *
     *          Also note that if you've made some changes to entity and you eg. throw an exception within this method
     *          your entity will be saved if it has eg Blameable / Timestampable traits attached.
     */
    public function afterUpdate(string &$id, RestDtoInterface $restDto, EntityInterface $entity): void
    {
    }
}
