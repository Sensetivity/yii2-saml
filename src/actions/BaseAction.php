<?php

namespace Sensetivity\yii2saml\actions;

use yii\base\Action;
use Yii;

/**
 * Base class for actions.
 */
abstract class BaseAction extends Action
{

    /**
     * This variable should be the component name of Sensetivity\yii2saml\Saml.
     * @var string
     */
    public $samlInstanceName = 'saml';

    /**
     * This variable hold the instance of Sensetivity\yii2saml\Saml.
     * @var \Sensetivity\yii2saml\Saml
     */
    protected $samlInstance;

    public function init()
    {
        parent::init();

        $this->samlInstance = Yii::$app->get($this->samlInstanceName);
    }

}
