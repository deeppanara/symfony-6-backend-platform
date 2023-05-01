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
 * /src/Rest/Traits/RestMethodHelper.php
 *
 */

namespace Platform\Rest\Traits;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnitOfWork;
use LogicException;
use Platform\DTO\RestDtoInterface;
use Platform\Rest\Interfaces\ControllerInterface;
use Platform\Rest\Traits\Methods\RestMethodProcessCriteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use UnexpectedValueException;
use function array_key_exists;
use function class_implements;
use function in_array;
use function is_array;
use function is_int;
use function sprintf;

/**
 * Trait RestMethodHelper
 */
trait RestMethodHelper
{
    use RestMethodProcessCriteria;

    /**
     * Method + DTO class names (key + value)
     *
     * @var array<string, string>
     */
    protected static array $dtoClasses = [];

    public function getDtoClass(?string $method = null): string
    {
        $dtoClass = $method !== null && array_key_exists($method, static::$dtoClasses)
            ? static::$dtoClasses[$method]
            : $this->getResource()->getDtoClass();

        $interfaces = class_implements($dtoClass);

        if (is_array($interfaces) && !in_array(RestDtoInterface::class, $interfaces, true)) {
            $message = sprintf(
                'Given DTO class \'%s\' is not implementing \'%s\' interface.',
                $dtoClass,
                RestDtoInterface::class,
            );

            throw new UnexpectedValueException($message);
        }

        return $dtoClass;
    }

    /**
     * @param array<int, string> $allowedHttpMethods
     */
    public function validateRestMethod(Request $request, array $allowedHttpMethods): void
    {
        // Make sure that we have everything we need to make this work
        if (!($this instanceof ControllerInterface)) {
            $message = sprintf(
                'You cannot use \'%s\' controller class with REST traits if that does not implement \'%s\'',
                static::class,
                ControllerInterface::class,
            );

            throw new LogicException($message);
        }

        if (!in_array($request->getMethod(), $allowedHttpMethods, true)) {
            throw new MethodNotAllowedHttpException($allowedHttpMethods);
        }
    }

    /**
     * @throws Throwable
     */
    public function handleRestMethodException(Throwable $exception, ?string $id = null): Throwable
    {
        if ($id !== null) {
            $this->detachEntityFromManager($id);
        }

        return $this->determineOutputAndStatusCodeForRestMethodException($exception);
    }

    /**
     * Getter method for exception code with fallback to `400` bad response.
     */
    private function getExceptionCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        return is_int($code) && $code !== 0 ? $code : Response::HTTP_BAD_REQUEST;
    }

    /**
     * Method to detach entity from entity manager so possible changes to it
     * won't be saved.
     *
     * @throws Throwable
     */
    private function detachEntityFromManager(string $id): void
    {
        $currentResource = $this->getResource();
        $entityManager = $currentResource->getRepository()->getEntityManager();

        // Fetch entity
        $entity = $currentResource->getRepository()->find($id);

        // Detach entity from manager if it's been managed by it
        if ($entity !== null
            /* @scrutinizer ignore-call */
            && $entityManager->getUnitOfWork()->getEntityState($entity) === UnitOfWork::STATE_MANAGED
        ) {
            $entityManager->clear();
        }
    }

    private function determineOutputAndStatusCodeForRestMethodException(Throwable $exception): Throwable
    {
        $code = $this->getExceptionCode($exception);

        $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);

        if ($exception instanceof NoResultException || $exception instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;

            $output = new HttpException($code, 'Not found', $exception, [], $code);
        } elseif ($exception instanceof NonUniqueResultException) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            $output = new HttpException($code, $exception->getMessage(), $exception, [], $code);
        } elseif ($exception instanceof HttpException) {
            if ($exception->getCode() === 0) {
                $output = new HttpException(
                    $exception->getStatusCode(),
                    $exception->getMessage(),
                    $exception->getPrevious(),
                    $exception->getHeaders(),
                    $code,
                );
            } else {
                $output = $exception;
            }
        }

        return $output;
    }
}