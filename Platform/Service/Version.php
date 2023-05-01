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
 * /src/Service/Version.php
 *
 */

namespace Platform\Service;

use Closure;
use Platform\Utils\JSON;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Throwable;
use function array_key_exists;
use function assert;
use function is_array;
use function is_string;


/**
 *
 */
class Version
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
        private readonly CacheInterface $appCacheApcu,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Method to get application version from cache or create new entry to
     * cache with version value from composer.json file.
     */
    public function get(): string
    {
        $output = '0.0.0';

        try {
            $output = (string)$this->appCacheApcu->get('application_version', $this->getClosure());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), $exception->getTrace());
        }

        return $output;
    }

    private function getClosure(): Closure
    {
        return function (ItemInterface $item): string {
            // One year
            $item->expiresAfter(31_536_000);

            $composerData = JSON::decode((string)file_get_contents($this->projectDir . '/composer.json'), true);

            assert(is_array($composerData));

            return array_key_exists('version', $composerData) && is_string($composerData['version'])
                ? $composerData['version']
                : '0.0.0';
        };
    }
}
