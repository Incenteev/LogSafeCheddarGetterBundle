<?php

/*
 * This file is part of the LogsafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logsafe\CheddarGetterBundle\Tests\DependencyInjection;

use Logsafe\CheddarGetterBundle\DependencyInjection\LogsafeCheddarGetterExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LogsafeCheddarGetterExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingUsername()
    {
        $loader = new LogsafeCheddarGetterExtension();
        $config = array('password' => 'foo');
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingPassword()
    {
        $loader = new LogsafeCheddarGetterExtension();
        $config = array('username' => 'me@example.com');
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testInvalidAdapter()
    {
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'me@example.com',
            'password' => 'foo',
            'http_adapter' => array('type' => 'invalid'),
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingAdapterId()
    {
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'me@example.com',
            'password' => 'foo',
            'http_adapter' => array('type' => 'service'),
        );
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testDefault()
    {
        $this->container = new ContainerBuilder();
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'me@example.com',
            'password' => 'foo',
        );
        $loader->load(array($config), $this->container);

        $this->assertParameter('me@example.com', 'logsafe_cheddar_getter.username');
        $this->assertParameter('foo', 'logsafe_cheddar_getter.password');
        $this->assertParameter(null, 'logsafe_cheddar_getter.product_code');
        $this->assertParameter(null, 'logsafe_cheddar_getter.product_id');
        $this->assertParameter('https://cheddargetter.com', 'logsafe_cheddar_getter.url');
        $this->assertAlias('logsafe_cheddar_getter.http_adapter.curl', 'logsafe_cheddar_getter.http_adapter');
        $this->assertHasDefinition('logsafe_cheddar_getter.client');
    }

    public function testAdapterService()
    {
        $this->container = new ContainerBuilder();
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'me@example.com',
            'password' => 'foo',
            'http_adapter' => array('type' => 'service', 'id' => 'acme.http_adapter'),
        );
        $loader->load(array($config), $this->container);

        $this->assertAlias('acme.http_adapter', 'logsafe_cheddar_getter.http_adapter');
    }

    public function testShortSyntaxAdapter()
    {
        $this->container = new ContainerBuilder();
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'me@example.com',
            'password' => 'foo',
            'http_adapter' => 'curl',
        );
        $loader->load(array($config), $this->container);

        $this->assertAlias('logsafe_cheddar_getter.http_adapter.curl', 'logsafe_cheddar_getter.http_adapter');
    }

    public function testCustomSettings()
    {
        $this->container = new ContainerBuilder();
        $loader = new LogsafeCheddarGetterExtension();
        $config = array(
            'username' => 'you@example.com',
            'password' => 'foobar',
            'product_code' => 'foo',
            'product_id' => 'baz',
            'url' => 'http://example.com'
        );
        $loader->load(array($config), $this->container);

        $this->assertParameter('you@example.com', 'logsafe_cheddar_getter.username');
        $this->assertParameter('foobar', 'logsafe_cheddar_getter.password');
        $this->assertParameter('foo', 'logsafe_cheddar_getter.product_code');
        $this->assertParameter('baz', 'logsafe_cheddar_getter.product_id');
        $this->assertParameter('http://example.com', 'logsafe_cheddar_getter.url');
        $this->assertAlias('logsafe_cheddar_getter.http_adapter.curl', 'logsafe_cheddar_getter.http_adapter');
    }

    private function assertAlias($value, $key)
    {
        $this->assertEquals($value, (string) $this->container->getAlias($key), sprintf('%s alias is correct', $key));
    }

    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->container->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    private function assertHasDefinition($id)
    {
        $this->assertTrue($this->container->hasDefinition($id) || $this->container->hasAlias($id));
    }

    private function assertNotHasDefinition($id)
    {
        $this->assertFalse($this->container->hasDefinition($id) || $this->container->hasAlias($id));
    }

    protected function tearDown()
    {
        unset($this->container);
    }
}
