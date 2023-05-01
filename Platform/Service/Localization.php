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
 * @date     01/05/23, 12:19 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Service/Localization.php
 *
 */

namespace Platform\Service;

use Closure;
use DateTimeImmutable;
use DateTimeZone;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Throwable;
use function explode;
use function floor;
use function str_replace;


/**
 *
 */
class Localization
{
    final public const DEFAULT_TIMEZONE = 'Europe/Helsinki';

    public function __construct(
        private readonly CacheInterface $appCacheApcu,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function getLanguages(): array
    {
        return Language::getValues();
    }

    /**
     * @return array<int, string>
     */
    public function getLocales(): array
    {
        return Locale::getValues();
    }

    /**
     * @return array<int, array{timezone: string, identifier: string,  offset: string, value: string}>
     */
    public function getTimezones(): array
    {
        $output = [];

        try {
            /** @var array<int, array{timezone: string, identifier: string, offset: string, value: string}> $output */
            $output = $this->appCacheApcu->get('application_timezone', $this->getClosure());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
        }

        return $output;
    }

    /**
     * @throws Throwable
     *
     * @return array<int, array{timezone: string, identifier: string,  offset: string, value: string}>
     */
    public function getFormattedTimezones(): array
    {
        $output = [];

        $identifiers = DateTimeZone::listIdentifiers();

        foreach ($identifiers as $identifier) {
            $dateTimeZone = new DateTimeZone($identifier);

            $dateTime = new DateTimeImmutable(timezone: $dateTimeZone);

            $hours = floor($dateTimeZone->getOffset($dateTime) / 3600);
            $minutes = floor(($dateTimeZone->getOffset($dateTime) - ($hours * 3600)) / 60);

            $hours = 'GMT' . ($hours < 0 ? $hours : '+' . $hours);
            $minutes = ($minutes > 0 ? $minutes : '0' . $minutes);

            $output[] = [
                'timezone' => explode('/', $identifier)[0],
                'identifier' => $identifier,
                'offset' => $hours . ':' . $minutes,
                'value' => str_replace('_', ' ', $identifier),
            ];
        }

        return $output;
    }

    private function getClosure(): Closure
    {
        return function (ItemInterface $item): array {
            // One year
            $item->expiresAfter(31_536_000);

            return $this->getFormattedTimezones();
        };
    }
}
