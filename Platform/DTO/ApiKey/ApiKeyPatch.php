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
 * @date     01/05/23, 12:06 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/DTO/ApiKey/ApiKeyPatch.php
 *
 */

namespace Platform\DTO\ApiKey;

use Platform\DTO\Traits\PatchUserGroups;


/**
 *
 */
class ApiKeyPatch extends ApiKey
{
    use PatchUserGroups;
}
