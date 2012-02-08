<?php

/*
 * This file is part of the LogsafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Logsafe\CheddarGetterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LogsafeCheddarGetterExtension extends Extension
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

            default:
                $container->setAlias('logsafe_cheddar_getter.http_adapter', sprintf('logsafe_cheddar_getter.http_adapter.%s', $config['http_adapter']['type']));
        }
        unset($config['http_adapter']);

        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('%s.%s', $this->getAlias(), $key), $value);
        }
    }
}
