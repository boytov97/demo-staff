<?php

class TestingController extends ControllerBase
{

    public function indexAction()
    {
        $settings = new Settings();

        echo '<pre>';
        print_r(  $settings->getByKey('max_late') );
        echo '</pre>';
    }
}

