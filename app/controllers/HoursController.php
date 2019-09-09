<?php

class HoursController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        return parent::initialize();
    }

    public function indexAction()
    {
        $query = $this->modelsManager->createQuery('SELECT * FROM Users ORDER BY id = :id: DESC');
        $users  = $query->execute([
            'id' => $this->identity['id']
        ]);

        if(count($users)) {
            $this->view->users = $users;
            $this->view->currentDate = date('Y-m-d');
            $this->view->datesMonth = $this->getDates(9, 2019);
        }

        $this->view->userName = $this->identity['name'];
    }

    protected function getDates($month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $datesMonth = [];

        for($i = 1; $i <= $daysInMonth; $i++)  {
            $mktime = mktime(0,0,0, $month, $i, $year);
            $date = date("l-M-Y", $mktime);
            $datesMonth[$i] = [
                'day' => date("l", $mktime),
                'date' => date('Y-m-d', $mktime)
            ];
        }

        return $datesMonth;
    }
}

