<?php

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends Controller
{
    protected $identity;

    public function initialize()
    {
        $this->identity = $this->auth->getIdentity();
        $this->view->setVar('logged_in', is_array($this->identity));

        $errorMessages = [];
        $successMessages = [];

        if(is_array($this->session->get('error_message'))) {
            $errorMessages = $this->session->get('error_message');
            $this->session->remove('error_message');
        }

        if(is_array( $this->session->get('success_message'))) {
            $successMessages = $this->session->get('success_message');
            $this->session->remove('success_message');
        }

        $this->view->setVar('errorMessages', $errorMessages);
        $this->view->setVar('successMessages', $successMessages);
    }

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {

                $this->flash->notice('You don\'t have access to this module: private. To get to this page you must log in');

                $dispatcher->forward([
                    'controller' => 'session',
                    'action' => 'login'
                ]);
                return false;
            }

            $actionName = $dispatcher->getActionName();

            // Check if the user have permission to the current option
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index'
                    ]);
                } else {
                    $dispatcher->forward([
                        'controller' => 'user_control',
                        'action' => 'index'
                    ]);
                }

                return false;
            }
        }
    }
}
