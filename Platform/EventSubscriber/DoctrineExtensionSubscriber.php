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
 * /src/EventSubscriber/DoctrineExtensionSubscriber.php
 *
 */

namespace Platform\EventSubscriber;

use Doctrine\ORM\NonUniqueResultException;
use Gedmo\Blameable\BlameableListener;
use Platform\Security\UserTypeIdentification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;


/**
 *
 */
class DoctrineExtensionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private readonly UserTypeIdentification $userTypeIdentification,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @throws NonUniqueResultException
     */
    public function onKernelRequest(): void
    {
        $user = $this->userTypeIdentification->getUser();

        if ($user !== null) {
            $this->blameableListener->setUserValue($user);
        }
    }
}
