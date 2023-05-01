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
 * /src/EventSubscriber/ResponseSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Platform\Service\Version;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;


/**
 *
 */
class ResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Version $version,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => [
                'onKernelResponse',
                10,
            ],
        ];
    }

    /**
     * Subscriber method to attach API version to every response.
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        // Attach new header
        $event->getResponse()->headers->add([
            'X-API-VERSION' => $this->version->get(),
        ]);
    }
}
