<?php

class NotWorkingDaysController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('admin');
        parent::initialize();
    }

    public function indexAction()
    {
        $this->view->authUser = $this->identity;
        $this->view->notWorkingDays = $this->getModel()->getAllNotHoliday();
    }

    public function createAction()
    {
        if ($this->request->isPost()) {
            $notWorkingDay = $this->getModel();

            $notWorkingDay->month = $this->request->getPost('month');
            $notWorkingDay->day = $this->request->getPost('day');
            $notWorkingDay->repeat = $this->request->getPost('repeat') ?: 'N';
            $notWorkingDay->createdAt = date('Y');

            if(!$notWorkingDay->save()) {
                $this->flash->error($notWorkingDay->getMessage());
            } else {

                $_POST = [];
                $this->flash->success("Not working day created successfully");
            }
        }

        $this->view->authUser = $this->identity;
        $this->view->months = $this->dateTime->getMonths();
        $this->view->defaultMonth = date('m');
    }

    public function deleteAction($id)
    {
        $notWorkingDay = NotWorkingDays::findFirstById($id);

        if (!$notWorkingDay->delete()) {
            $this->flash->error($notWorkingDay->getMessages());
        } else {
            $this->flash->success("The not working day was successfully deleted");
        }

        return $this->response->redirect('notWorkingDays');
    }

    protected function getModel()
    {
        return new NotWorkingDays();
    }
}

