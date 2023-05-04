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
 * /src/Rest/ControllerCollection.php
 *
 */

namespace Platform\Rest;

use Closure;
use Countable;
use IteratorAggregate;
use Platform\Collection\CollectionTrait;
use Platform\Rest\Interfaces\ControllerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use function sprintf;

/*
 *
 * @method ControllerInterface get(string $className)
 * @method IteratorAggregate<int, ControllerInterface> getAll()
 *
 * @template T<ControllerInterface>
 */

/**
 *
 */
class ControllerCollection implements Countable
{
    use CollectionTrait;

    /**
     * Collection constructor.
     *
     * @phpstan-param IteratorAggregate<int, ControllerInterface> $items
     */
    public function __construct(
        #[TaggedIterator('app.rest.controller')]
        protected readonly IteratorAggregate $items,
        protected readonly LoggerInterface $logger,
    ) {
    }

    public function getErrorMessage(string $className): string
    {
        return sprintf('REST controller \'%s\' does not exist', $className);
    }

    public function filter(string $className): Closure
    {
        return static fn (ControllerInterface $restController): bool => $restController instanceof $className;
    }
}
