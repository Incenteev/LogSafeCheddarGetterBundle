Features
========

This bundle allows to easily use the official CheddarGetter_ client in your
Symfony2 project by configuring it through the DIC.

Installation
============

Bring in the vendor libraries
-----------------------------

This can be done in two different ways:

**Method #1**) Use the bin/vendor script

```ini
[cheddargetter-client-php]
    git=http://github.com/marcguyer/cheddargetter-client-php.git
    target=cheddargetter-client-php/CheddarGetter

[LogsafeCheddarGetterBundle]
    git=http://github.com/LogSafe/LogsafeCheddarGetterBundle.git
    target=bundles/Logsafe/CheddarGetterBundle
```

**Method #2**) Use git submodules

```bash
$ git submodule add http://github.com/marcguyer/cheddargetter-client-php.git vendor/cheddargetter-client-php/CheddarGetter
$ git submodule add http://github.com/LogSafe/LogsafeCheddarGetterBundle.git vendor/bundles/Logsafe/CheddarGetterBundle
```

Register the autoloading
------------------------

```php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    'LogSafe'  => __DIR__.'/../vendor/bundles',
    // your other namespaces
));
$loader->registerPrefixes(array(
    'CheddarGetter'  => __DIR__.'/../vendor/cheddargetter-client-php',
    // your other prefixes
));
```

Add LogsafeCheddarGetterBundle to your application kernel
---------------------------------------------------------

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        // ...
        new Logsafe\CheddarGetterBundle\LogsafeCheddarGetterBundle(),
        // ...
    );
}
```

Configure the bundle
====================

You need to configure your CheddarGetter account for the API.

```yaml
# app/config/config.yml
logsafe_cheddar_getter:
    username: me@example.org
    password: SECRET_PASSWORD
```

Use the CheddarGetter client
============================

The client is available through the container under the ``logsafe_cheddar_getter.client``
id. Read the official documentation_ to see its use.

Configuration reference
=======================

Here is the complete configuration with its default values.

```yaml
logsafe_cheddar_getter:
    username: ~ #required
    password: ~ #required
    url: https://cheddargetter.com
    product_code: ~
    product_id: ~
    http_adapter:
        type: curl # can be "curl", "buzz" or "service"
        id: ~ # the service id when using "service" (mandatory) or "buzz" (optional)
```

.. _CheddarGetter: https://cheddargetter.com/developers
.. _documentation: http://cheddargetter.com/php-client/docs
