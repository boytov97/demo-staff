<?php

use Phalcon\Http\Response;

class HoursController extends ControllerBase
{
    protected $month;

    protected $year;

    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        return parent::initialize();
    }

    public function beforeExecuteRoute()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function indexAction()
    {
        $currentDate = date('Y-m-d');

        if(!$this->request->isPost()) {
            if($month = $this->request->get('month')) {
                $this->month = $month;
            }

            if($year = $this->request->get('year')) {
                $this->year = $year;
            }
        }

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

        $datesMonth = $this->getDates($this->month, $this->year);

        if(count($users)) {
            $this->view->lastStartTime = $lastStartTime;
            $this->view->users = $users;
            $this->view->currentDate = $currentDate;
            $this->view->datesMonth = $datesMonth;
        }

        $this->view->authUser = $this->identity;
        $this->view->years = $this->dateTime->getYears();
        $this->view->months = $this->dateTime->getMonths();
        $this->view->defaultYear = $this->year;
        $this->view->defaultMonth = $this->month;
        $this->view->workingDaysCount = $this->getWorkingDaysCount($datesMonth);
        $this->view->totalPerMonth = $this->getTotalPerMonth($this->month, $this->year);


        $this->view->percentOfTotal = $this->getPercentOfTotal($this->getWorkingDaysCount($datesMonth), $this->getTotalPerMonth($this->month, $this->year));
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
                    'total' => $this->dateTime->getDifference($firstStartEnd->start)
                ]);

                $hour->save();

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode([
                        'urlForNewStartEnd' => $urlForNewStartEnd,
                        'action' => $this->request->getPost('action'),
                        'startEnds' => $hour->startEnds,
                        'total' => $this->dateTime->getDifference($firstStartEnd->start)
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
            'total' => $this->dateTime->getDifference($firstStartEnd->start)
        ]);

        $hour->save();

        $response = new Response();

        $response->setStatusCode(200, 'OK');
        $response->setContent(json_encode([
            'total' => $this->dateTime->getDifference($firstStartEnd->start)
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

    /**
     *
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getDates($month, $year)
    {
        $items = NotWorkingDays::find([
            'conditions' => 'month = :month:',
            'bind'       => [
                'month' => $month,
            ]
        ]);

        return $this->dateTime->getDates($month, $year, $items);
    }

    /**
     *
     *
     * @param $datesMonth
     * @return int
     */
    protected function getWorkingDaysCount($datesMonth)
    {
        $workingDaysCount = 0;

        foreach ($datesMonth as $dateMonth) {
            if($dateMonth['working_day']) {
                $workingDaysCount++;
            }
        }

        return $workingDaysCount;
    }

    protected function getTotalPerMonth($month, $year)
    {
        $createdAt = $year . '-' . $month . '%';

        $hours = Hours::find([
            'conditions' => 'createdAt LIKE :createdAt: AND usersId = :id:',
            'bind'       => [
                'createdAt' => $createdAt,
                'id' => $this->identity['id']
            ]
        ])->toArray();

        $totalPerMonthTimeStamp = $this->dateTime->getTotalTimeStampOfHours($hours);

        $hour = round($totalPerMonthTimeStamp / 60 / 60);
        $minute = ($totalPerMonthTimeStamp / 60) % 60;
        $second = $totalPerMonthTimeStamp % 60;

        return $hour . ':' . $minute . ':' . $second;
    }

    protected function getPercentOfTotal($workingDaysCount, $totalPerMonth)
    {
        $workingHoursCount = $workingDaysCount * 8 * 60 * 60;
        $totalPerMonthTimeStamp = strtotime($totalPerMonth);

        return date('H:i:s', 226800);

        /*return round(($totalPerMonthTimeStamp - strtotime("00:00:00")) / ($workingHoursCount / 100), 2);*/
    }
}
