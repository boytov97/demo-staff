<?php

use Phalcon\Http\Response;

class IndividuallyWdController extends \Phalcon\Mvc\Controller
{
    public function createOrUpdateAction($userId, $createdAt)
    {
        if($this->request->isPost()) {
            $messages = [];

            $response = new Response();
            $checked = $this->request->getPost('checked');

            $individually = IndividuallyWd::findFirst([
                'conditions' => 'userId = :userId: AND createdAt = :createdAt:',
                'bind' => [
                    'userId' => $userId,
                    'createdAt' => $createdAt
                ]
            ]);

            if(!$individually) {
                $individually = $this->getIndividuallyWdModel();

                $individually->userId = $userId;
                $individually->createdAt = $createdAt;
                $individually->working_day = $checked;

                if(!$individually->save()) {
                    $messages = $individually->getMessages();
                }
            } else {

                if(!$individually->delete()) {
                    $messages = $individually->getMessages();
                }
            }


            if (count($messages)) {
                $response->setStatusCode(500, 'Internal Server Error');
                $response->setContent(json_encode($messages));
            } else {

                $response->setStatusCode(200, 'OK');
                $response->setContent(json_encode(['success' => 'success']));
            }

            return $response;
        }
    }

    protected function getIndividuallyWdModel()
    {
        return new IndividuallyWd();
    }
}

