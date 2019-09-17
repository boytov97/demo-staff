<?php

class AdminController extends ControllerBase
{
    public function initialize()
    {
        //$this->view->setTemplateBefore('admin');

        return parent::initialize();
    }

    public function indexAction()
    {

        $this->view->authUser = $this->identity;
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

    protected function getUsersModel()
    {
        return new Users();
    }
}

