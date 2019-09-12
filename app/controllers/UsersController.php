<?php

class UsersController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('public');
        return parent::initialize();
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

                    //redirect
                }
            }
        }

        $this->view->form = $form;
    }
}

