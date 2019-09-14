<?php

class TestingController extends ControllerBase
{

    public function indexAction($month = 9, $year = 2019)
    {
        $currentDate = date('Y-m-d');

        $day = date('d', strtotime($currentDate));
        $month = date('m', strtotime($currentDate));
        $year = date('Y', strtotime($currentDate));

        $mktime = mktime(0,0,0, $month, $day, $year);

        echo '<pre>';
        print_r($mktime);
        echo '<br>';
        print_r(strtotime($currentDate));
        echo '</pre>';
    }
}

