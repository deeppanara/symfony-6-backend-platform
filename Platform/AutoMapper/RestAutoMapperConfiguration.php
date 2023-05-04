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
 * /src/AutoMapper/RestAutoMapperConfiguration.php
 *
 */

namespace Platform\AutoMapper;

use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MapperInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 */
abstract class RestAutoMapperConfiguration implements AutoMapperConfiguratorInterface
{
    /**
     * Classes to use specified request mapper.
     *
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [];

    public function __construct(
        protected readonly MapperInterface $requestMapper,
    ) {
    }

    /**
     * Use this method to register your mappings.
     *
     * @psalm-suppress UndefinedThisPropertyFetch
     */
    public function configure(AutoMapperConfigInterface $config): void
    {
        foreach (static::$requestMapperClasses as $requestMapperClass) {
            $config
                ->registerMapping(Request::class, $requestMapperClass)
                ->useCustomMapper($this->requestMapper);
        }
    }
}
