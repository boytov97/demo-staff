<?php

class TestingController extends ControllerBase
{

    public function indexAction($month = 9, $year = 2019)
    {
        $currentDate = 46800 - (strtotime('09:00:00') - strtotime("00:00:00"));

        echo '<pre>';
        print_r($currentDate);
        echo '</pre>';
    }
}

