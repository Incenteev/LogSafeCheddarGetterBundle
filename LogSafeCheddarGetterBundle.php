<?php

/*
 * This file is part of the LogSafeCheddarGetterBundle package.
 *
 * (c) LogSafe <http://logsafe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LogSafe\CheddarGetterBundle;

use LogSafe\CheddarGetterBundle\DependencyInjection\LogSafeCheddarGetterExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Christophe Coevoet <stof@notk.org>
 */
class LogSafeCheddarGetterBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new LogSafeCheddarGetterExtension();
        }

        return $this->extension;
    }
}
