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
 * /src/EventSubscriber/AcceptLanguageSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use function in_array;


/**
 *
 */
class AcceptLanguageSubscriber implements EventSubscriberInterface
{
    // Supported locales
    final public const LOCALE_EN = 'en';
    final public const LOCALE_FI = 'fi';

    final public const SUPPORTED_LOCALES = [
        self::LOCALE_EN,
        self::LOCALE_FI,
    ];

    public function __construct(
        #[Autowire('%locale%')]
        private readonly string $locale,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => [
                'onKernelRequest',
                // Note that this needs to at least `100` to get translation messages as expected
                100,
            ],
        ];
    }

    /**
     * Method to change used locale according to current request.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $locale = $request->headers->get('Accept-Language', $this->locale);

        // Ensure that given locale is supported, if not fallback to default.
        if (!in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = $this->locale;
        }

        $request->setLocale($locale);
    }
}
