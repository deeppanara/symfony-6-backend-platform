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
 * /src/Security/SecurityUser.php
 *
 */

namespace Platform\Security;

use Platform\Entity\User;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 *
 */
class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private readonly string $identifier;
    private readonly string | null $password;
    private readonly Language $language;
    private readonly Locale $locale;
    private readonly string $timezone;

    /**
     * SecurityUser constructor.
     *
     * @param array<int, string> $roles
     */
    public function __construct(
        User $user,
        private readonly array $roles = [],
    ) {
        $this->identifier = $user->getId();
        $this->password = $user->getPassword();
        $this->language = $user->getLanguage();
        $this->locale = $user->getLocale();
        $this->timezone = $user->getTimezone();
    }

    public function getUuid(): string
    {
        return $this->getUserIdentifier();
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
