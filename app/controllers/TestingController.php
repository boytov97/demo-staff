<?php

class TestingController extends ControllerBase
{

    /**
     * Оставил для проверки повторении не рабочих дней
     *
     */
    public function indexAction()
    {
        $not_working = NotWorkingDays::find([
            'conditions' => 'month = :month: AND (repeat = :repeatNo: AND createdAt = :createdAt:) OR repeat = :repeatYes: AND month = :month:',
            'bind' => [
                'month' => 9,
                'repeatNo' => 'N',
                'createdAt' => '2019',
                'repeatYes' => 'Y',
            ]
        ]);

        echo '<pre>';
        print_r( $not_working);
        echo '</pre>';
    }
}

