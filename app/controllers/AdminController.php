<?php

use Phalcon\Http\Response;

class AdminController extends ControllerBase
{
    public $hourForDay = 9;

    protected $month;
    
    protected $year;

    protected $validation;

    public function initialize()
    {
        $this->validation = new AdminValidation();
        parent::initialize();
    }

    public function beforeExecuteRoute()
    {
        $this->month = date('m');
        $this->year = date('Y');
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
                    $total     = $this->dateTime->getTotalDifference($startEnds);
                    $assignment['total'] = $total;

                    $notWorkingDays = $this->getNotWorkingDaysModel()->getAllByMonth($this->month);

                    if (!$this->dateTime->isNotWorkingDay($hour->createdAt, $notWorkingDays)) {
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

    public function createUserAction()
    {
        $form = new CreateUserForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $user = $this->getUsersModel()->checkUniqueness($this->request->getPost('email'));

                if(!$user) {
                    $user = new Users();

                    $user->name = $this->request->getPost('name');
                    $user->login = $this->request->getPost('login');
                    $user->email = $this->request->getPost('email');
                    $user->password = $this->security->hash($this->request->getPost('password'));
                    $user->profilesId = $this->request->getPost('profilesId');

                    if(!$user->save()) {
                        $this->flash->error($user->getMessages());
                    } else {
                        $this->flash->success('User successfully created');

                        $_POST = [];

                        return $this->response->redirect('admin');
                    }
                } else {
                    $this->flash->error('User with this email already created!');
                }
            }
        }

        $this->view->authUser = $this->identity;
        $this->view->action = $this->url->get(['for' => 'admin-create-user']);
        $this->view->form = $form;
    }

    public function editAction($userId)
    {
        $this->view->pick('admin/createUser');

        $user = Users::findFirstById($userId);
        $form = new CreateUserForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $user->assign([
                    'name' => $this->request->getPost('name'),
                    'profilesId' => $this->request->getPost('profilesId'),
                ]);

                if(!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->success('User successfully updated!');

                    $_POST = [];

                    return $this->response->redirect('admin');
                }
            }
        }

        $this->view->authUser = $this->identity;
        $this->view->action = $this->url->get(['for' => 'admin-users-edit', 'id' => $user->id]);
        $this->view->form = $form;
        $this->view->user = $user;
    }

    public function updateActivityAction($userId)
    {
        if ($this->request->isPost()) {
            $user = Users::findFirstById($userId);

            $user->assign([
                'active' => $this->request->getPost('active')
            ]);

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {

                return $this->response->redirect('admin/users');
            }
        }
    }

    public function usersAction()
    {
        $users = $this->getUsersModel()->getAll();

        $this->view->authUser = $this->identity;
        $this->view->users = $users;
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
}

