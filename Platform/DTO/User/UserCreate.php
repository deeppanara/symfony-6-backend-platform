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
 * /src/Rest/DTO/User/UserCreate.php
 *
 */

namespace Platform\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;


/**
 *
 */
class UserCreate extends User
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *      min = 8,
     *      max = 255,
     *  )
     */
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 8, max: 255)]
    protected string $password = '';
}
