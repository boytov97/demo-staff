<?php

use Phalcon\Http\Response;

class HoursController extends ControllerBase
{
    protected $month;

    protected $year;

    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        $this->month = date('m');
        $this->year = date('Y');

        return parent::initialize();
    }

    public function beforeExecuteRoute()
    {
        if($this->request->isAjax()) {

            if($month = $this->request->getPost('month')) {
                $this->month = $month;
            }

            if($year = $this->request->getPost('year')) {
                $this->year = $year;
            }
        }
    }

    public function indexAction()
    {
        $currentDate = date('Y-m-d');

        $query = $this->modelsManager->createQuery('SELECT * FROM Users ORDER BY id = :id: DESC');
        $users  = $query->execute([
            'id' => $this->identity['id']
        ]);

        $hour = $this->create($currentDate);

        $startEnds = StartEnd::find([
            'conditions' => 'hourId = :hourId:',
            'bind'       => [
                'hourId' => $hour->id,
            ],
        ]);

        $lastStartTime = null;
        $startEndsCount = count($startEnds);
        $i = 0;
        foreach($startEnds as $key => $startEnd) {
            if(++$i === $startEndsCount) {
                $lastStartTime = $startEnd->start;
            }
        }

        if(count($users)) {
            $this->view->lastStartTime = $lastStartTime;
            $this->view->users = $users;
            $this->view->currentDate = $currentDate;
            $this->view->datesMonth = $this->getDates(9, 2019);
        }

        $this->view->authUser = $this->identity;
        $this->view->years = $this->getYears();
    }

    public function updateAction($id, $startEndId)
    {
        if($this->request->isPost()) {
            $message = [];
            $urlForNewStartEnd = null;

            if($this->request->getPost('action') == 'start') {
                $message = $this->storeStartTime($startEndId);
            }

            if($this->request->getPost('action') == 'stop') {
                $startEnd = StartEnd::findFirst($startEndId);

                $startEnd->assign([
                    'end' => date('H:i:s')
                ]);

                if(!$startEnd->save()) {
                    $message = $this->flash->error($startEnd->getMessages());
                }

                $newStartEnd = new StartEnd();
                $newStartEnd->hourId = $id;

                if(!$newStartEnd->save()) {
                    $message = $this->flash->error($newStartEnd->getMessages());
                }

                $urlForNewStartEnd = $this->url->get(['for' => 'hours-update', 'id' => $id, 'startEndId' => $newStartEnd->id]);
            }

            $firstStartEnd = StartEnd::findFirst([
                'conditions' => 'hourId = :hourId:',
                'bind'       => [
                    'hourId' => $id,
                ],
            ]);

            $response = new Response();

            if(count($message)) {
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($message));

                return $response;
            } else {

                $hour = Hours::findFirst($id);

                $hour->assign([
                    'total' => $this->getTotal($firstStartEnd->start)
                ]);

                $hour->save();

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode([
                        'urlForNewStartEnd' => $urlForNewStartEnd,
                        'action' => $this->request->getPost('action'),
                        'startEnds' => $hour->startEnds,
                        'total' => $this->getTotal($firstStartEnd->start)
                    ]));

                return $response;
            }
        }
    }

    public function updateTotalAction($id)
    {
        $firstStartEnd = StartEnd::findFirst([
            'conditions' => 'hourId = :hourId:',
            'bind'       => [
                'hourId' => $id,
            ],
        ]);

        $hour = Hours::findFirst($id);
        $hour->assign([
            'total' => $this->getTotal($firstStartEnd->start)
        ]);

        $hour->save();

        $response = new Response();

        $response->setStatusCode(200, 'OK');
        $response->setContent(json_encode([
            'total' => $this->getTotal($firstStartEnd->start)
        ]));

        return $response;
    }

    protected function storeStartTime($startEndId)
    {
        $startEnd = StartEnd::findFirst($startEndId);

        $startEnd->assign([
            'start' => date('H:i:s')
        ]);

        if(!$startEnd->save()) {
            return $this->flash->error($startEnd->getMessages());
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

            $startEnd = new StartEnd();
            $startEnd->hourId = $hour->id;

            if(!$startEnd->save()) {
                $this->flash->error($hour->getMessages());
            }
        }

        return $hour;
    }

    protected function getDates($month, $year)
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $datesMonth = [];

        for ($i = 1; $i <= $daysInMonth; $i++)  {
            $mktime = mktime(0,0,0, $month, $i, $year);

            $datesMonth[$i] = [
                'day'  => date("l", $mktime),
                'date' => date('Y-m-d', $mktime)
            ];
        }

        return $datesMonth;
    }

    protected function getTotal($start)
    {
        $strStart = date('Y-m-d') . ' ' . $start;
        $strEnd = date('Y-m-d H:i:s');

        $dteStart = new DateTime($strStart);
        $dteEnd   = new DateTime($strEnd);

        $dteDiff  = $dteStart->diff($dteEnd);

        return $dteDiff->format("%H:%I:%S");
    }

    protected function getYears()
    {
        $years = [];

        for ($i = 10; $i >= 0; $i--) {
            $string = date('Y') . ' -' . $i . ' year';

            $years[date('Y', strtotime($string))] = date('Y', strtotime($string));
        }

        return $years;
    }
}
