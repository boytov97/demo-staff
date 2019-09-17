<?php

class TestingController extends ControllerBase
{

    public function indexAction()
    {

        echo '<pre>';
        print_r( strtotime('03:33') - strtotime('00:00:00'));
        echo '</pre>';
    }
}

