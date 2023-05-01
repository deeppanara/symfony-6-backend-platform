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
 * /src/EventSubscriber/BodySubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use JsonException;
use Platform\Utils\JSON;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use function in_array;
use function is_array;


/**
 *
 */
class BodySubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [
                'onKernelRequest',
                10,
            ],
        ];
    }

    /**
     * Implementation of BodySubscriber event. Purpose of this is to convert JSON request data to proper request
     * parameters.
     *
     * @throws JsonException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Get current request
        $request = $event->getRequest();

        // If request has some content and is JSON type convert it to request parameters
        if ($request->getContent() !== '' && $this->isJsonRequest($request)) {
            $this->transformJsonBody($request);
        }
    }

    /**
     * Method to determine if current Request is JSON type or not.
     */
    private function isJsonRequest(Request $request): bool
    {
        return in_array($request->getContentTypeFormat(), [null, 'json', 'txt'], true);
    }

    /**
     * Method to transform JSON type request to proper request parameters.
     *
     * @throws JsonException
     */
    private function transformJsonBody(Request $request): void
    {
        $data = JSON::decode($request->getContent(), true);

        if (is_array($data)) {
            $request->request->replace($data);
        }
    }
}
