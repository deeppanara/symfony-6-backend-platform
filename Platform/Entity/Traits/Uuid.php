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
 * @date     01/05/23, 12:27 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Entity/Traits/Uuid.php
 *
 */

namespace Platform\Entity\Traits;

use Platform\Rest\UuidHelper;
use Ramsey\Uuid\UuidInterface;

/**
 * Trait Uuid
 */
trait Uuid
{
    public function getUuid(): UuidInterface
    {
        return $this->id;
    }

    protected function createUuid(): UuidInterface
    {
        return UuidHelper::getFactory()->uuid1();
    }
}
