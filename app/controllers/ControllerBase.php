<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    protected $identity;

    public function initialize()
    {
        $this->identity = $this->auth->getIdentity();
        $this->view->setVar('logged_in', is_array($this->identity));
        $this->view->setTemplateBefore('public');
    }
}
