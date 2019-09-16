<?php

class UsersController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('protected');
        return parent::initialize();
    }

    public function indexAction()
    {
        $form = new ProfileForm();
        $user = Users::findFirstById($this->identity['id']);

        $this->view->user = $user;
        $this->view->authUser = $this->identity;
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $form = new ProfileForm();
        $user = Users::findFirstById($this->identity['id']);

        if ($this->request->isPost()) {

            if($form->isValid(array_merge($this->request->getPost(), $_FILES))) {

                $image = $user->image;

                if ($this->request->hasFiles('image') == true) {

                    foreach ($this->request->getUploadedFiles() as $file){
                        $image = $this->config->uploads->path . 'profile/' . time() . '-' . $file->getName();

                        if($file->moveTo(PUBLIC_PATH . $image)) {
                            $this->flash->success('Your image was successfully changed');
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

                    $this->flash->success('Your name was successfully changed');

                    return $this->response->redirect('users');
                }
            }
        }
    }

    public function changePasswordAction()
    {
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
}

