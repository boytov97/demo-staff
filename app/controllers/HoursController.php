<?php

use Phalcon\Http\Response;

class HoursController extends ControllerBase
{
    public $hourForDay = 9;

    public $beginning = '09:00:00';

    public $total = null;

    public $less = null;

    public $updateUrl = null;

    protected $month;

    protected $year;

    protected $settings;

    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        $this->settings = new Settings();
        $this->month = date('m');
        $this->year = date('Y');

        return parent::initialize();
    }

    public function indexAction()
    {
        $currentDate = date('Y-m-d');

        if ($month = $this->request->get('month')) {
            $this->month = $month;
        }

        if ($year = $this->request->get('year')) {
            $this->year = $year;
        }

        $authUserId = $this->identity['id'];
        $users = $this->modelsManager->createBuilder()->from('Users')
            ->orderBy("id = $authUserId DESC")->getQuery()->execute();

        $hour = $this->create($currentDate);
        $startEnds = $this->getStartEndModel()->findByHourId($hour->id);

        if ($hour->total) {

            $hour->assign([
                'total' => $this->dateTime->getTotalDifference($startEnds)
            ]);

            if (!$hour->save()) {
                $this->flash->error($hour->getMessages());
            }
        }

        $lastStartTime = null;
        $startEndsCount = count($startEnds);
        $i = 0;
        foreach ($startEnds as $key => $startEnd) {
            if (++$i === $startEndsCount) {
                $lastStartTime = $startEnd->start;
            }
        }

        $notWorkingDays = $this->getNotWorkingDaysModel()->getAllByMonth($this->month);
        $datesMonth = $this->dateTime->getDates($this->month, $this->year, $notWorkingDays);
        $totalSecondPerMonth = $this->getTotalSecondPerMonth($this->month, $this->year, $notWorkingDays);
        $workingDaysCount = $this->getWorkingDaysCount($datesMonth);

        if (count($users)) {
            $this->view->lastStartTime = $lastStartTime;
            $this->view->users = $users;
            $this->view->hoursCreatedAts = $this->dateTime->getArrayOfUsersHoursCreatedAt($users);
            $this->view->currentDate = $currentDate;
            $this->view->currentTimestamp = strtotime($currentDate);
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
        $this->view->authUserlateCount = $this->getAuthUserLateCount($this->month, $this->year);
        $this->view->lateCountPerMonth = $this->getLateCountPerMonth($this->month, $this->year);
        $this->view->maxLate = $this->settings->getValueByKey('max_late');
        $this->view->lateUsers = $this->getBeenLateUsers($this->month, $this->year);
    }

    public function updateAction($id, $startEndId)
    {
        if ($this->request->isPost()) {
            $hour = Hours::findFirst($id);
            $firstStartEnd = $this->getStartEndModel()->findFirstByHourId($hour->id);
            $notWorkingDays = $this->getNotWorkingDaysModel()->getAllByMonth($this->month);

            $message = [];
            $entity = [];

            if ($this->request->getPost('action') == 'start') {

                if (!$firstStartEnd->start) {
                    $beginning = $this->settings->getValueByKey('beginning') ?: $this->beginning;

                    if (!$this->dateTime->isNotWorkingDay(date('H:i:s'), $notWorkingDays)) {
                        if (strtotime($beginning) < strtotime('now')) {
                            $entity['late'] = 1;
                        }
                    }
                }

                $startEnd = StartEnd::findFirst($startEndId);

                $startEnd->assign([
                    'start' => date('H:i:s')
                ]);

                if (!$startEnd->save()) {
                    $message = $startEnd->getMessages();
                } else {

                    $entity['less'] = null;
                }
            }

            if ($this->request->getPost('action') == 'stop') {
                $startEnd = StartEnd::findFirst($startEndId);

                $startEnd->assign([
                    'stop' => date('H:i:s')
                ]);

                if (!$startEnd->save()) {
                    $message = $startEnd->getMessages();
                }

                $newStartEnd = $this->getStartEndModel();
                $newStartEnd->hourId = $id;

                if (!$newStartEnd->save()) {
                    $message = $newStartEnd->getMessages();
                }

                $this->updateUrl = $this->url->get(['for' => 'hours-update', 'id' => $id, 'startEndId' => $newStartEnd->id]);

                $startEnds = $this->getStartEndModel()->findByHourId($hour->id);
                $this->total = $this->dateTime->getTotalDifference($startEnds);

                if (!$this->dateTime->isNotWorkingDay(date('H:i:s'), $notWorkingDays)) {
                    $this->less = (($this->hourForDay * 3600) > $this->dateTime->parseHour($this->total)) ?
                        $this->dateTime->getDiffBySecond($this->hourForDay * 3600, $this->dateTime->parseHour($this->total)) : null;
                }

                $entity['total'] = $this->total;
                $entity['less'] = $this->less;
            }

            $hour->assign($entity);

            if (!$hour->save()) {
                $message = $hour->getMessages();
            }

            $response = new Response();

            if (count($message)) {
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($message));
            } else {

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode([
                    'success'   => true,
                    'updateUrl' => $this->updateUrl,
                    'action'    => $this->request->getPost('action'),
                    'startEnds' => $hour->startEnds,
                    'hourId'    => $hour->id,
                    'total'     => $this->total,
                    'less'      => $this->less,
                    'late'      => $entity['late'] ?: 0,
                ]));
            }

            return $response;
        }
    }

    public function updateTotalAction($id)
    {
        $hour = Hours::findFirst($id);
        $startEnds = $this->getStartEndModel()->findByHourId($hour->id);

        $hour->assign([
            'total' => $this->dateTime->getTotalDifference($startEnds)
        ]);

        $response = new Response();

        if (!$hour->save()) {
            $response->setStatusCode(500, 'Internal Server Error');
            $response->setContent(json_encode($hour->getMessages()));
        } else {

            $response->setStatusCode(200, 'OK');
            $response->setContent(json_encode([
                'hourId' => $hour->id,
                'total'  => $this->dateTime->getTotalDifference($startEnds)
            ]));
        }

        return $response;
    }

    /**
     * Создаеть счётчик за сегодня если оно не создано
     *
     * @param $currentDate
     * @return Hours|\Phalcon\Mvc\Model\ResultInterface
     */
    protected function create($currentDate)
    {
        $hour = $this->getModel()->findFirstByUserIdAndCreatedAt($this->identity['id'], $currentDate);

        if (!$hour) {
            $hour = new Hours();

            $hour->usersId = $this->identity['id'];
            $hour->createdAt = date('Y-m-d');

            if (!$hour->save()) {
                $this->flash->error($hour->getMessages());
            }

            $startEnd = new StartEnd();
            $startEnd->hourId = $hour->id;

            if (!$startEnd->save()) {
                $this->flash->error($hour->getMessages());
            }
        }

        return $hour;
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
            if ($dateMonth['working_day']['woDay']) {
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
    protected function getTotalSecondPerMonth($month, $year, $notWorkingDays)
    {
        $createdAt = $year . '-' . $month . '%';
        $hours = $this->getModel()->getByCreatedAt($createdAt, $this->identity['id']);

        return $this->dateTime->getTotalSecondOfHours($hours, $notWorkingDays);
    }

    /**
     * Возвращает сумму отработанных часов
     *
     * @param $totalSecondPerMonth
     * @return string
     */
    protected function getTotalPerMonth($totalSecondPerMonth)
    {
        $hour = 0;
        $minute = 0;

        if ($totalSecondPerMonth >= 60) {
            $hour = floor($totalSecondPerMonth / 60 / 60);
            $minute = ($totalSecondPerMonth / 60) % 60;
        }

        if (strlen((string)$hour) == 1) {
            $hour = '0' . $hour;
        }

        if (strlen((string)$minute) == 1) {
            $minute = '0' . $minute;
        }

        return $hour . ':' . $minute;
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
        $percentOfTotal = 0;

        if ($totalSecondPerMonth >= 60) {

            $workingSecondsCount = $workingDaysCount * ($this->hourForDay - 1) * 60 * 60;
            $percentOfTotal = round($totalSecondPerMonth / ($workingSecondsCount / 100), 2);
        }

        return $percentOfTotal;
    }

    /**
     * Возвращает сумму опаздании всех пользователей
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getLateCountPerMonth($month, $year)
    {
        $createdAt = $year . '-' . $month . '%';

        return $this->getModel()->getLateCountByCreatedAt($createdAt);
    }

    /**
     * Возвращает сумму опаздании аутентифицированного пользователя
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getAuthUserLateCount($month, $year)
    {
        $createdAt = $year . '-' . $month . '%';

        return $this->getModel()->getAuthLateCountByCreatedAt($createdAt, $this->identity['id']);
    }

    /**
     * Возвращает три главных опаздунов
     *
     * @param $month
     * @param $year
     * @return mixed
     */
    protected function getBeenLateUsers($month, $year)
    {
        $createdAt = $year . '-' . $month . '%';

        $lateUsers = $this->modelsManager->createBuilder()
            ->from('Users')
            ->columns([
                'Users.id',
                'Users.name',
                'Users.image',
                'SUM(uh.late) AS beenLate',
            ])
            ->join('Hours', 'uh.usersId = Users.id', 'uh')
            ->where('uh.late = 1')->andWhere('createdAt LIKE "' . $createdAt . '"')
            ->groupBy('Users.id')->orderBy('SUM(late) DESC')->limit(3)->getQuery()
            ->execute();

        return $lateUsers;
    }

    protected function getModel()
    {
        return new Hours();
    }

    protected function getStartEndModel()
    {
        return new StartEnd();
    }

    protected function getNotWorkingDaysModel()
    {
        return new NotWorkingDays();
    }
}
