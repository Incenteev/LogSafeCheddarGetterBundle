<?php

/*
 * This file is part of the LogsafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logsafe\CheddarGetterBundle\Http;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adapter implementation using the Symfony2 abstraction for getting and setting http related data
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class SymfonyAdapter implements \CheddarGetter_Http_AdapterInterface
{
    private $container;

    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    private $response;

    private $cookies = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether a cookie exists.
     *
     * @param string $name Cookie name
     * @return boolean
     */
    public function hasCookie($name)
    {
        return $this->hasRequest() && $this->getRequest()->cookies->has($name);
    }

    /**
     * Gets the value of a cookie.
     *
     * @param string $name Cookie name
     * @return mixed
     */
    public function getCookie($name)
    {
        if (!$this->hasRequest()) {
            return null;
        }

        return $this->getRequest()->cookies->get($name);
    }

    /**
     * Sets the value of a cookie.
     *
     * @param string $name Cookie name
     * @param string $data Value of the cookie
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param boolean $secure
     * @param boolean $httpOnly
     */
    public function setCookie($name, $data, $expire, $path, $domain, $secure = false, $httpOnly = false)
    {
        $cookie = new Cookie($name, $data, $expire, $path, $domain, $secure, $httpOnly);

        if (null === $this->response) {
            $this->cookies[] = $cookie;
        } else {
            $this->response->headers->setCookie($cookie);
        }
    }

    /**
     * Gets a request parameter.
     *
     * null is returned if the key is not set.
     *
     * @param string $key
     * @return mixed
     */
    public function getRequestValue($key)
    {
        if (!$this->hasRequest()) {
            return null;
        }

        return $this->getRequest()->get($key);
    }

    /**
     * @return boolean
     */
    public function hasReferrer()
    {
        return $this->hasRequest() && $this->getRequest()->headers->has('referer');
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        if (!$this->hasRequest()) {
            return null;
        }

        return $this->getRequest()->headers->get('referer');
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;

        foreach ($this->cookies as $cookie) {
            $response->headers->setCookie($cookie);
        }
        $this->cookies = array();
    }

    /**
     * @return boolean
     */
    protected function hasRequest()
    {
        return $this->container->has('request');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->container->get('request');
    }
}
