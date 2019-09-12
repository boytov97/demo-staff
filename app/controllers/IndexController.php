<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('public');
        return parent::initialize();
    }

    public function indexAction()
    {

    }
}

