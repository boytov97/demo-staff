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

    public function hourAction($wd)
    {


        $individuallyWds = $this->getIndividuallyWdModel()->getByWorkingDay(1);
        $individuallyNotWds = $this->getIndividuallyWdModel()->getByWorkingDay(0);

        $forUsers = [];

        foreach ($individuallyNotWds as $individuallyWd) {
            if($individuallyWd->createdAt === '2019-09-24') {
                $forUsers[] = $individuallyWd->userId;
            }
        }


        echo '<pre>';
        print_r($individuallyWds[0]);
        echo '</pre>';
    }

    protected function getIndividuallyWdModel()
    {
        return new IndividuallyWd();
    }
}

