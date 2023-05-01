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
 * /src/AutoMapper/User/RequestMapper.php
 *
 */

namespace Platform\AutoMapper\User;

use InvalidArgumentException;
use Platform\AutoMapper\RestRequestMapper;
use Platform\Entity\UserGroup;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Platform\Resource\UserGroupResource;
use Throwable;
use function array_map;


/**
 *
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'username',
        'firstName',
        'lastName',
        'email',
        'language',
        'locale',
        'timezone',
        'userGroups',
        'password',
    ];

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
    ) {
    }

    /**
     * @param array<int, string> $userGroups
     *
     * @return array<int, UserGroup>
     *
     * @throws Throwable
     */
    protected function transformUserGroups(array $userGroups): array
    {
        return array_map(
            fn (string $userGroupUuid): UserGroup => $this->userGroupResource->getReference($userGroupUuid),
            $userGroups,
        );
    }

    protected function transformLanguage(string $language): Language
    {
        return Language::tryFrom($language) ?? throw new InvalidArgumentException('Invalid language');
    }

    protected function transformLocale(string $locale): Locale
    {
        return Locale::tryFrom($locale) ?? throw new InvalidArgumentException('Invalid locale');
    }
}
