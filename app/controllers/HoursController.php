<?php

use Phalcon\Mvc\View;
use Phalcon\Http\Response;

class HoursController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        return parent::initialize();
    }

    public function indexAction()
    {
        $currentDate = date('Y-m-d');

        $query = $this->modelsManager->createQuery('SELECT * FROM Users ORDER BY id = :id: DESC');
        $users  = $query->execute([
            'id' => $this->identity['id']
        ]);

        $this->create($currentDate);

        if(count($users)) {
            $this->view->users = $users;
            $this->view->currentDate = $currentDate;
            $this->view->datesMonth = $this->getDates(9, 2019);
        }

        $this->view->userName = $this->identity['name'];
    }

    public function updateAction()
    {
        $this->view->pick('hours/table');

        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => false,
            View::LEVEL_BEFORE_TEMPLATE => false
        ]);

        if($this->request->isPost()) {
            $start = $this->request->getPost('start') ? date('G:i:s') : null;
            $end = $this->request->getPost('end') ? date('G:i:s') : null;

            $hour = Hours::findFirst([
                'usersId = ?0 AND createdAt = ?1',
                'bind' => [
                    $this->identity['id'],
                    date('Y-m-d')
                ]
            ]);

            //total

            $hour->assign([
                'start' => $hour->start ?: $start,
                'end'   => $end,
            ]);

            $hour->save();

            if(!$hour->save()) {
                $response = new Response();
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($hour->getMessages()));

                return $response;
            } else {
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
        }
    }

    protected function create($currentDate)
    {
        $hour = Hours::findFirst([
            'usersId = ?0 AND createdAt = ?1',
            'bind' => [
                $this->identity['id'],
                $currentDate
            ]
        ]);

        if(!$hour) {
            $hour = new Hours();

            $hour->usersId = $this->identity['id'];
            $hour->createdAt = date('Y-m-d');

            if(!$hour->save()) {
                $this->flash->error($hour->getMessages());
            }
        }
    }

    protected function getDates($month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $datesMonth = [];

        for ($i = 1; $i <= $daysInMonth; $i++)  {
            $mktime = mktime(0,0,0, $month, $i, $year);

            $datesMonth[$i] = [
                'day' => date("l", $mktime),
                'date' => date('Y-m-d', $mktime)
            ];
        }

        return $datesMonth;
    }
}

