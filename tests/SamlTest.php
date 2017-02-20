<?php

namespace sensetivity\yii2saml\tests;

use sensetivity\yii2saml\Saml;

class SamlTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstance()
    {
        $instance = new Saml();
        $this->assertNotEquals($instance, null);
    }

}
