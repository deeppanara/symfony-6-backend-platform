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
 * /src/ValueResolver/RestDtoValueResolver.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace Platform\ValueResolver;

use AutoMapperPlus\AutoMapperInterface;
use Generator;
use Platform\DTO\RestDtoInterface;
use Platform\Rest\Controller;
use Platform\Rest\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Throwable;
use function count;
use function explode;
use function in_array;

/*
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

/**
 *
 */
class RestDtoValueResolver implements ValueResolverInterface
{
    private const CONTROLLER_KEY = '_controller';

    /**
     * @var array<int, string>
     */
    private array $supportedActions = [
        Controller::ACTION_CREATE,
        Controller::ACTION_UPDATE,
        Controller::ACTION_PATCH,
    ];

    /**
     * @var array<string, string>
     */
    private array $actionMethodMap = [
        Controller::ACTION_CREATE => Controller::METHOD_CREATE,
        Controller::ACTION_UPDATE => Controller::METHOD_UPDATE,
        Controller::ACTION_PATCH => Controller::METHOD_PATCH,
    ];

    private ?string $controllerName = null;
    private ?string $actionName = null;

    /**
     * RestDtoValueResolver constructor.
     *
     */
    public function __construct(
        private readonly ControllerCollection $controllerCollection,
        private readonly AutoMapperInterface $autoMapper,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $bits = explode('::', (string)$request->attributes->get(self::CONTROLLER_KEY, ''));

        if (count($bits) !== 2) {
            return false;
        }

        [$controllerName, $actionName] = $bits;

        $output = $argument->getType() === RestDtoInterface::class
            && in_array($actionName, $this->supportedActions, true)
            && $this->controllerCollection->has($controllerName);

        if ($output === true) {
            $this->controllerName = $controllerName;
            $this->actionName = $actionName;
        }

        return $output;
    }

    /**
     * @throws Throwable
     *
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        if (!$this->supports($request, $argument) || $this->controllerName === null) {
            return [];
        }

        $dtoClass = $this->controllerCollection
            ->get($this->controllerName)
            ->getDtoClass($this->actionMethodMap[$this->actionName] ?? null);

        yield $this->autoMapper->map($request, $dtoClass);
    }
}
