<?php

class UsersController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setVar('title', 'Users');
        parent::initialize();
    }

    public function profileAction()
    {
        $this->view->setTemplateBefore('protected');
        $this->view->setVar('title', 'Profile');

        $form = new ProfileForm();
        $user = Users::findFirstById($this->identity['id']);

        if ($this->request->isPost()) {

            if($form->isValid(array_merge($this->request->getPost(), $_FILES))) {
                $image = $user->image;

                if ($this->request->hasFiles('image') == true) {

                    foreach ($this->request->getUploadedFiles() as $file){
                        $image = $this->config->uploads->path . 'profile/' . time() . '-' . $file->getName();

                        if($file->moveTo(PUBLIC_PATH . $image)) {
                            $this->flash->success('Your image was successfully changed.');
                        }
                    }
                }

                $user->assign([
                    'name' => $this->request->getPost('name'),
                    'image' => $image,
                ]);

                if(!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {

                    $this->session->set('auth-identity', [
                        'id' => $user->id,
                        'name' => $user->name,
                        'profile' => $user->profile->name
                    ]);

                    $_POST = [];
                    $_FILES = [];

                    $this->flash->success('Your data has been successfully updated.');
                }
            }
        }

        $this->view->user = $user;
        $this->view->authUser = $this->identity;
        $this->view->form = $form;
    }

    public function changePasswordAction()
    {
        $this->view->setTemplateBefore('protected');
        $this->view->setVar('title', 'Profile');
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost())) {
                $user = $this->auth->getUser();

                $user->assign([
                    'password' => $this->security->hash($this->request->getPost('password'))
                ]);

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->success('Your password was successfully changed');

                    $_POST = [];

                    return $this->response->redirect('users');
                }
            }
        }

        $this->view->authUser = $this->identity;
        $this->view->form = $form;
    }

    public function deleteUploadsAction()
    {
        $user = Users::findFirstById($this->identity['id']);

        if(is_file(PUBLIC_PATH . $user->image)) {
            unlink(PUBLIC_PATH . $user->image);
        }

        $user->assign([
            'image' => null,
        ]);

        if(!$user->save()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success('Your image was successfully deleted');
        }

        return $this->response->redirect('users');
    }

    public function createAction()
    {
        $this->view->setTemplateBefore('admin');
        $form = new CreateUserForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {

                $login = $this->request->getPost('login');
                $email = $this->request->getPost('email');

                $user = $this->getModel()->checkUniqueness($email, $login);

                if(!$user) {
                    $user = new Users();

                    $user->name = $this->request->getPost('name');
                    $user->login = $this->request->getPost('login');
                    $user->email = $this->request->getPost('email');
                    $user->password = $this->security->hash($this->request->getPost('password'));
                    $user->profilesId = $this->request->getPost('profilesId');

                    if(!$user->save()) {
                        $this->session->set('error_message', $user->getMessages());
                    } else {
                        $this->session->set('success_message', ['success' => 'User successfully created']);

                        $_POST = [];

                        return $this->response->redirect('users');
                    }
                } else {
                    $this->flash->error('User with this email or login already created!');
                }
            }
        }

        $this->view->authUser = $this->identity;
        $this->view->action = $this->url->get(['for' => 'admin-create-user']);
        $this->view->form = $form;
    }

    public function editAction($userId)
    {
        $this->view->setTemplateBefore('admin');
        $this->view->pick('users/create');

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
                $this->session->set('error_message', $user->getMessages());
            } else {

                return $this->response->redirect('users');
            }
        }
    }

    public function indexAction()
    {
        $this->view->setTemplateBefore('admin');
        $users = $this->getModel()->getAll();

        $this->view->authUser = $this->identity;
        $this->view->users = $users;
    }

    protected function getModel()
    {
        return new Users();
    }
}

