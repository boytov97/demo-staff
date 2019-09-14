<?php

use Phalcon\Http\Response;

class HoursController extends ControllerBase
{
    public $hourForDay = 9;

    public $beginning = '09:00:00';

    protected $month;

    protected $year;

    protected $settings;

    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        $this->settings = new Settings();
        $this->beginning = $this->settings->getByKey('beginning');
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

        if($month = $this->request->get('month')) {
            $this->month = $month;
        }

        if($year = $this->request->get('year')) {
            $this->year = $year;
        }

        $authUserId = $this->identity['id'];
        $users = $this->modelsManager->createBuilder()->from('Users')
            ->orderBy("id = $authUserId DESC")->getQuery()->execute();

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

        $datesMonth = $this->dateTime->getDates($this->month, $this->year, $this->getNotWorkingDays($this->month));
        $totalSecondPerMonth = $this->getTotalSecondPerMonth($this->month, $this->year);
        $workingDaysCount = $this->getWorkingDaysCount($datesMonth);

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
        $this->view->workingHoursCount = $workingDaysCount * ($this->hourForDay - 1);
        $this->view->totalPerMonth = $this->getTotalPerMonth($totalSecondPerMonth);
        $this->view->percentOfTotal = $this->getPercentOfTotal($workingDaysCount, $totalSecondPerMonth);
    }

    public function updateAction($id, $startEndId)
    {
        if($this->request->isPost()) {
            $hour = Hours::findFirst($id);

            $message = [];
            $total = null;
            $updateUrl = null;

            if($this->request->getPost('action') == 'start') {
                $message = $this->storeStartTime($startEndId);
            }

            if($this->request->getPost('action') == 'stop') {
                $startEnd = StartEnd::findFirst($startEndId);

                $startEnd->assign([
                    'end' => date('H:i:s')
                ]);

                if (!$startEnd->save()) {
                    $message = $startEnd->getMessages();
                }

                $newStartEnd = new StartEnd();
                $newStartEnd->hourId = $id;

                if (!$newStartEnd->save()) {
                    $message = $newStartEnd->getMessages();
                }

                $updateUrl = $this->url->get(['for' => 'hours-update', 'id' => $id, 'startEndId' => $newStartEnd->id]);

                $firstStartEnd = StartEnd::findFirst([
                    'conditions' => 'hourId = :hourId:',
                    'bind' => [
                        'hourId' => $id,
                    ],
                ]);

                $total = $this->dateTime->getDifference($firstStartEnd->start);

                $hour->assign([
                    'total' => $total
                ]);

                if(!$hour->save()) {
                    $message = $hour->getMessages();
                }
            }

            $response = new Response();

            if(count($message)) {
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($message));
            } else {

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode([
                        'updateUrl' => $updateUrl,
                        'action' => $this->request->getPost('action'),
                        'startEnds' => $hour->startEnds,
                        'total' => $total
                    ]));
            }

            return $response;
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

        $response = new Response();

        if(!$hour->save()) {
            $response->setStatusCode(500, 'Internal Server Error');
            $response->setContent(json_encode($hour->getMessages()));
        } else {

            $response->setStatusCode(200, 'OK');
            $response->setContent(json_encode([
                'total' => $this->dateTime->getDifference($firstStartEnd->start)
            ]));
        }

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

    /**
     * Создаеть счётчик если сегодня рабочий день
     *
     * @param $currentDate
     * @return Hours|\Phalcon\Mvc\Model\ResultInterface
     */
    protected function create($currentDate)
    {
        $hour = Hours::findFirst([
            'usersId = ?0 AND createdAt = ?1',
            'bind' => [
                $this->identity['id'],
                $currentDate
            ]
        ]);

        if(!$hour and !$this->dateTime->isNotWorkingDay($currentDate, $this->getNotWorkingDays($this->month))) {
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
     * Возвращает не рабочие дни который добавил админ
     *
     * @param $month
     * @return NotWorkingDays|NotWorkingDays[]|\Phalcon\Mvc\Model\ResultSetInterface
     */
    protected function getNotWorkingDays($month)
    {
        $items = NotWorkingDays::find([
            'conditions' => 'month = :month:',
            'bind'       => [
                'month' => $month,
            ]
        ]);

        return $items;
    }

    /**
     * Возвращает количество рабочих дней
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

    /**
     * Возвращает timestamp суммы отработтаных часов
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getTotalSecondPerMonth($month, $year)
    {
        $createdAt = $year . '-' . $month . '%';

        $hours = Hours::find([
            'conditions' => 'createdAt LIKE :createdAt: AND usersId = :id:',
            'bind'       => [
                'createdAt' => $createdAt,
                'id' => $this->identity['id']
            ]
        ])->toArray();

        return $this->dateTime->getTotalSecondOfHours($hours, $this->beginning);
    }

    /**
     * Возвращает сумму отработанных часов
     *
     * @param $totalSecondPerMonth
     * @return string
     */
    protected function getTotalPerMonth($totalSecondPerMonth)
    {
        $hour = round($totalSecondPerMonth / 60 / 60);
        $minute = ($totalSecondPerMonth / 60) % 60;
        $second = $totalSecondPerMonth % 60;

        return $hour . ':' . $minute . ':' . $second;
    }

    /**
     * Возвращает процент отработанных часов
     *
     * @param $workingDaysCount
     * @param $totalSecondPerMonth
     * @return float
     */
    protected function getPercentOfTotal($workingDaysCount, $totalSecondPerMonth)
    {
        $workingSecondsCount = $workingDaysCount * ($this->hourForDay - 1) * 60 * 60;

        return round($totalSecondPerMonth / ($workingSecondsCount / 100), 2);
    }
}
