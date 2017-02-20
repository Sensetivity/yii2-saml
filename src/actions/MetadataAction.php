<?php

namespace Sensetivity\yii2saml\actions;

use Yii;
use yii\web\Response;

/**
 * This provides action for displaying metadata for this Service Provider. It uses OneLogin_Saml2_Auth configuration and then generate metadata in xml.
 */
class MetadataAction extends BaseAction
{

    /**
     * Display Service Provider Metadata in xml format.
     * @return void
     */
    public function run()
    {
        header('Content-Type: text/xml; charset=utf-8');
        echo $this->samlInstance->getMetadata();
    }

}
