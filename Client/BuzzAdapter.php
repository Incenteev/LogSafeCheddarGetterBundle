<?php

/*
 * This file is part of the LogSafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSafe\CheddarGetterBundle\Client;

use Buzz\Browser;
use Buzz\Message\Request;

class BuzzAdapter implements \CheddarGetter_Client_AdapterInterface
{
    private $buzz;

    public function __construct(Browser $buzz = null)
    {
        if (null === $buzz) {
            $buzz = new Browser();
        }
        $this->buzz = $buzz;
    }

    /**
     * Execute CheddarGetter API request
     *
     * @param string $url Url to the API action
     * @param string $username Username
     * @param string $password Password
     * @param array|null $args HTTP post key value pairs
     * @return string Body of the response from the CheddarGetter API
     * @throws \CheddarGetter_Client_Exception
     */
    public function request($url, $username, $password, array $args = null)
    {
        $authHeader = sprintf('Authorization: Basic %s', base64_encode($username.':'.$password));

        try {
            if ($args) {
                $response = $this->buzz->submit($url, $args, Request::METHOD_POST, array($authHeader));
            } else {
                $response = $this->buzz->get($url, array($authHeader));
            }
        } catch (\RuntimeException $e) {
            throw new \CheddarGetter_Client_Exception($e->getMessage(), \CheddarGetter_Client_Exception::UNKNOWN, $e);
        }

        return $response->getContent();
    }
}
