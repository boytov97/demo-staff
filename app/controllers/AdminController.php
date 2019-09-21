<?php

use Phalcon\Http\Response;

class AdminController extends ControllerBase
{
    public $hourForDay = 9;

    protected $beginning = '09:00:00';

    protected $month;
    
    protected $year;

    protected $validation;

    public function initialize()
    {
        $this->view->setVar('title', 'Admin');

        $this->validation = new AdminValidation();
        $this->month = date('m');
        $this->year = date('Y');

        parent::initialize();
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
        
        $users = Users::find();

        $this->createCounter($users, $currentDate);

        $notWorkingDays = $this->getNotWorkingDaysModel()->getAllByMonth($this->month);
        $datesMonth = $this->dateTime->getDates($this->month, $this->year, $notWorkingDays);

        $this->view->currentDate = date('Y-m-d');
        $this->view->authUser = $this->identity;
        $this->view->admin = true;
        $this->view->users = $users;
        $this->view->years = $this->dateTime->getYears();
        $this->view->months = $this->dateTime->getMonths();
        $this->view->defaultYear = $this->year;
        $this->view->defaultMonth = $this->month;
        $this->view->datesMonth = $datesMonth;
        $this->view->beginning = $this->getSettingsModel()->getValueByKey('beginning');
        $this->view->maxLate = $this->getSettingsModel()->getValueByKey('max_late');
    }

    public function updateStartEndAction($id)
    {
        if ($this->request->isPost()) {
            $entity   = [];
            $messages = [];

            $validationMessages = $this->validation->validate($this->request->getPost());
            $response = new Response();

            $validationArrayMessages = [];

            foreach ($validationMessages as $validationMessage) {
                $validationArrayMessages[] = $validationMessage->getMessage();
            }

            if(!count($validationArrayMessages)) {

                $assignment = [];

                $startEnd  = StartEnd::findFirstById($id);

                $startEnd->assign([
                    'start' => $this->request->getPost('start'),
                    'stop'  => $this->request->getPost('stop')
                ]);

                if(!$startEnd->save()) {
                    $messages = $startEnd->getMessages();
                } else {

                    $hour      = Hours::findFirstById($startEnd->hour->id);
                    $startEnds = $this->getStartEndModel()->findByHourId($hour->id);
                    $firstStartEnd = $this->getStartEndModel()->findFirstByHourId($hour->id);
                    $total     = $this->dateTime->getTotalDifference($startEnds);
                    $assignment['total'] = $total;

                    if($firstStartEnd->id == $id) {
                        $beginning = $this->getSettingsModel()->getValueByKey('beginning') ?: $this->beginning;

                        if (strtotime($beginning) < strtotime($this->request->getPost('start'))) {
                            $assignment['late'] = 1;
                        } else {
                            $assignment['late'] = 0;
                        }
                    }

                    $notWorkingDays = $this->getNotWorkingDaysModel()->getAllByMonth($this->month);
                    $lastStopTime = null;
                    $startEndsCount = count($startEnds);
                    $i = 0;
                    foreach ($startEnds as $key => $startEnd) {
                        if (++$i === $startEndsCount) {
                            $lastStopTime = $startEnd->stop;
                        }
                    }

                    if (!$this->dateTime->isNotWorkingDay($hour->createdAt, $notWorkingDays) && $lastStopTime) {
                        $assignment['less'] = (($this->hourForDay * 3600) > $this->dateTime->parseHour($total)) ?
                            $this->dateTime->getDiffBySecond($this->hourForDay * 3600, $this->dateTime->parseHour($total)) : null;
                    }

                    $hour->assign($assignment);

                    if(!$hour->save()) {
                        $messages = $hour->getMessages();
                    } else {
                        $entity['hourId'] = $startEnd->hour->id;
                        $entity['assignment'] = $assignment;
                    }
                }
            }

            if (count($messages)) {
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($messages));
            } else {
                $entity['validation'] = $validationArrayMessages;

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode($entity));
            }

            return $response;
        }
    }

    protected function createCounter($users, $currentDate)
    {
        foreach ($users as $user) {
            $hour = $this->getHoursModel()->findFirstByUserIdAndCreatedAt($user->id, $currentDate);

            if (!$hour) {
                $hour = new Hours();

                $hour->usersId = $user->id;
                $hour->createdAt = $currentDate;

                if (!$hour->save()) {
                    $this->flash->error($hour->getMessages());
                }

                $startEnd = new StartEnd();
                $startEnd->hourId = $hour->id;

                if (!$startEnd->save()) {
                    $this->flash->error($hour->getMessages());
                }
            }
        }
    }

    protected function getUsersModel()
    {
        return new Users();
    }

    protected function getNotWorkingDaysModel()
    {
        return new NotWorkingDays();
    }

    protected function getStartEndModel()
    {
        return new StartEnd();
    }

    protected function getHoursModel()
    {
        return new Hours();
    }

    protected function getSettingsModel()
    {
        return new Settings();
    }
}

