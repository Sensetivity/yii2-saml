Yii 2 Saml
==========

[![Build Status](https://travis-ci.org/asasmoyo/yii2-saml.svg?branch=master)](https://travis-ci.org/asasmoyo/yii2-saml)

Connect Yii 2 application to a Saml Identity Provider for Single Sign On

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist sensetivity/yii2-saml "*"
```

or add

```
"sensetivity/yii2-saml": "*"
```

to the require section of your `composer.json` file.

Configuration
-------------

Register ``sensetivity\yii2saml\Saml`` to your components in ``config/web.php``.

```php
'components' => [
    'saml' => [
        'class' => 'sensetivity\yii2saml\Saml',
        'configFileName' => '@app/config/saml.php', // OneLogin_Saml config file (Optional)
    ]
]
```

This component requires a ``OneLogin_Saml`` configuration stored in a php file. The default value for ``configFileName`` is ``@app/config/saml.php`` so make sure to create this file before. This file must returns the ``OneLogin_Saml`` configuration. See this [link](https://github.com/onelogin/php-saml/blob/master/settings_example.php) for example configuration.

```php
<?php

$urlManager = Yii::$app->urlManager;
$spBaseUrl = $urlManager->getHostInfo() . $urlManager->getBaseUrl();

return [
    'sp' => [
        'entityId' => $spBaseUrl.'/saml/metadata',
        'assertionConsumerService' => [
            'url' => $spBaseUrl.'/saml/acs',
        ],
        'singleLogoutService' => [
            'url' => $spBaseUrl.'/saml/sls',
        ],
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
    ],
    'idp' => [
        'entityId' => 'identity-provider',
        'singleSignOnService' => [
            'url' => 'https://idp.com/sso',
        ],
        'singleLogoutService' => [
            'url' => 'https://idp.com/sls',
        ],
        'x509cert' => '<x509cert string>',
    ],
];
```

Usage
-----

This extension provides 4 actions:

1. LoginAction

    This actions will initiate login process to Identity Provider specified in config file. To use this action, just register this action to your actions in your controller.

    ```php
    <?php

    namespace app\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;


    class SamlController extends Controller {

        // Remove CSRF protection
        public $enableCsrfValidation = false;

        public function actions() {
            return [
                'login' => [
                    'class' => 'sensetivity\yii2saml\actions\LoginAction'
                ]
            ];
        }

    }
    ```

    Now you can login to your Identity Provider by visiting ``saml/login``.

2. AcsAction

    This action will process saml response sent by Identity Provider after succesfull login. You can register a callback to do some operation like read the attributes sent by Identity Provider and create a new user from that attributes. To use this action just register this action to you controllers's actions.

    ```php
    <?php

    namespace app\controllers;

    use Yii;
    use yii\web\Controller;
    use yii\helpers\Url;


    class SamlController extends Controller {

        // Remove CSRF protection
        public $enableCsrfValidation = false;

        public function actions() {
            return [
                ...
                'acs' => [
                    'class' => 'sensetivity\yii2saml\actions\AcsAction',
                    'attributeMapping' => [
                        'http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname' => 'username',
                        'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname' => 'first_name',
                        'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname' => 'last_name',
                        'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' => 'email',
                        'http://schemas.xmlsoap.org/claims/Group' => 'groups',
                    ],
                    'successCallback' => [$this, 'callback'],
                    'successUrl' => Url::to('site/welcome'),
                ]
            ];
        }

        /**
         * @param array $attributes mapped attributes sent by Identity Provider.
         * @param string $nameId nameId attribute sent by Identity Provider.
         * @param array $rawAttributes sent by Identity Provider.
         */
        public function callback($attributes, $nameId, $rawAttributes) {
            // do something
        }
    }
    ```

    **NOTE: Make sure to register the acs action's url to ``AssertionConsumerService`` and the sls actions's url to ``SingleLogoutService`` (if supported) in the Identity Provider.**

3. MetadataAction

    This action will show metadata of you application in xml. To use this action, just register the action to your controller's action.

    ```php
    <?php

        public function actions() {
            return [
                ...
                'metadata' => [
                    'class' => 'sensetivity\yii2saml\actions\MetadataAction'
                ]
            ];
        }
    ```

4. LogoutAction

    This action will initiate SingleLogout process to Identity Provider. To use this action, just register this action to your controller's actions.

    ```php
    <?php

        public function actions() {
            return [
                ...
                'logout' => [
                    'class' => 'sensetivity\yii2saml\actions\LogoutAction',
                    'returnTo' => Url::to('site/bye'),
                ]
            ];
        }
    ```

5. SlsAction

    This action will process saml logout request/response sent by Identity Provider. To use this action just register this action to you controllers's actions.

    ```php
    <?php

        public function actions() {
            ...

            return [
                ...
                'sls' => [
                    'class' => 'sensetivity\yii2saml\actions\SlsAction',
                    'successUrl' => Url::to('site/bye'),
                ]
            ]
        }
    ```

Usage
-----

If the SAMLResponse is rejected, add to the SAML settings the parameter
``` 
'debug' => true,
```
and the reason will be prompted.


LICENCE
-------

MIT Licence
