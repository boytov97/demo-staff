<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('public');
        $this->view->setVar('title', 'Home');
        return parent::initialize();
    }

    public function indexAction()
    {
        $this->view->authUser = $this->identity;
    }
}

