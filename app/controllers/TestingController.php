<?php

class TestingController extends ControllerBase
{

    public function indexAction($month = 9, $year = 2019)
    {

        $createdAtMin = $year . '-' . $month . '-01';
        $createdAtMax = $year . '-' . $month . '-31';

        $query = $this->modelsManager->createQuery('SELECT Hours.total FROM Hours WHERE Hours.createdAt >= :createdAtMin: AND Hours.createdAt <= :createdAtMax: AND Hours.usersId = :id:');
        $totals  = $query->execute([
            'createdAtMin' => $createdAtMin,
            'createdAtMax' => $createdAtMax,
            'id'        => $this->identity['id']
        ]);

        $totalPerMonth = '00:00:00';
        $totalPerMonthTimeStamp = strtotime($totalPerMonth);

        foreach($totals as $total) {
            if($total->total) {
                $totalPerMonthTimeStamp = $totalPerMonthTimeStamp + strtotime($total->total);
            }
        }

        $totalPerMonthTimeStamp = $totalPerMonthTimeStamp  - strtotime("00:00:00");

        if ($totalPerMonthTimeStamp) {
            $totalPerMonth = date('H:i:s', $totalPerMonthTimeStamp);
        }

        echo '<pre>';
        print_r($totalPerMonth);
        echo '</pre>';
    }
}

