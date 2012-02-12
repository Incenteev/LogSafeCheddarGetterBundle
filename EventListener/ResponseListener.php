<?php

/*
 * This file is part of the LogSafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSafe\CheddarGetterBundle\EventListener;

use LogSafe\CheddarGetterBundle\Http\SymfonyAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ResponseListener injects the Response into the SymfonyAdapter to set the cookies.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResponseListener implements EventSubscriberInterface
{
    private $adapter;

    public function __construct(SymfonyAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Filters the Response.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->adapter->setResponse($event->getResponse());
    }

    static public function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}
