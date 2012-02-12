<?php

/*
 * This file is part of the LogSafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSafe\CheddarGetterBundle\Tests\Client;

use LogSafe\CheddarGetterBundle\Client\BuzzAdapter;

class BuzzAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException CheddarGetter_Client_Exception
     */
    public function testError()
    {
        $buzz = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();
        $buzz->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \RuntimeException()));

        $adapter = new BuzzAdapter($buzz);
        $adapter->request('example.com', 'me', 'foo');
    }

    /**
     * @expectedException CheddarGetter_Client_Exception
     */
    public function testErrorPost()
    {
        $buzz = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();
        $buzz->expects($this->once())
            ->method('submit')
            ->will($this->throwException(new \RuntimeException()));

        $adapter = new BuzzAdapter($buzz);
        $adapter->request('example.com', 'me', 'foo', array('q' => 't'));
    }

    public function testRequestGet()
    {
        $response = $this->getMockBuilder('Buzz\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('hello'));

        $buzz = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();
        $buzz->expects($this->once())
            ->method('get')
            ->will($this->returnValue($response));

        $adapter = new BuzzAdapter($buzz);
        $this->assertEquals('hello', $adapter->request('example.com', 'me', 'foo'));
    }

    public function testRequestPost()
    {
        $response = $this->getMockBuilder('Buzz\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('hello'));

        $buzz = $this->getMockBuilder('Buzz\Browser')
            ->disableOriginalConstructor()
            ->getMock();
        $buzz->expects($this->once())
            ->method('submit')
            ->will($this->returnValue($response));

        $adapter = new BuzzAdapter($buzz);
        $this->assertEquals('hello', $adapter->request('example.com', 'me', 'foo', array('q' => 't')));
    }
}
