<?php

/*
 * This file is part of the LogSafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSafe\CheddarGetterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LogSafeCheddarGetterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('client.xml');

        switch ($config['http_adapter']['type']) {
            case 'service':
                $container->setAlias('logsafe_cheddar_getter.http_adapter', $config['http_adapter']['id']);
                break;

            case 'buzz':
                if (isset($config['http_adapter']['id'])) {
                    // Uses a preconfigured Buzz\Browser (for instance the "buzz" service from SensioBuzzBundle)
                    // Otherwise, a new one will be created by the adapter
                    $container->setAlias('logsafe_cheddar_getter.http_adapter.buzz.browser', new Alias($config['http_adapter']['id'], false));
                }
                $container->setAlias('logsafe_cheddar_getter.http_adapter', 'logsafe_cheddar_getter.http_adapter.buzz');
                break;

            default:
                $container->setAlias('logsafe_cheddar_getter.http_adapter', sprintf('logsafe_cheddar_getter.http_adapter.%s', $config['http_adapter']['type']));
        }
        unset($config['http_adapter']);

        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('%s.%s', $this->getAlias(), $key), $value);
        }
    }

    public function getAlias()
    {
        return 'logsafe_cheddar_getter';
    }
}
