<?php

use Phalcon\Mvc\User\Component;

class Auth extends Component
{
    public function check($loginPost)
    {
        if($this->isEmail($loginPost['login'])) {
            $user = Users::findFirstByEmail($loginPost['login']);
        } else {
            $user = $this->getModel()->findFirstByLogin($loginPost['login']);
        }

        if(!$user) {
            throw new AuthException('Wrong email/password combination');
        }

        if(!$this->security->checkHash($loginPost['password'], $user->password)) {
            throw new AuthException('Wrong password combination');
        }

        $this->checkUserActivate($user);

        // Check if the remember me was selected
        if (isset($loginPost['remember'])) {
            $this->createRememberEnvironment($user);
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
        ]);
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();

            $userId = $this->findFirstByToken($token);
            if ($userId) {
                $this->deleteToken($userId);
            }

            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new AuthException('The user does not exist');
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Staff\Models\Users
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {

            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new AuthException('The user does not exist');
            }

            return $user;
        }

        return false;
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberTokens::findFirst([
                    'usersId = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name
                        ]);

                        return $this->response->redirect('users');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect($this->url->get(['for' => 'session-index']));
    }

    /**
     * Проверка на активности
     *
     * @param Users $user
     * @throws Exception
     */
    public function checkUserActivate(Users $user)
    {
        if ($user->active != 'Y') {
            throw new AuthException('User with this email / login is not exist!');
        }
    }

    protected function createRememberEnvironment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->usersId = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save()) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Returns the current token user
     *
     * @param string $token
     * @return boolean
     */
    protected function findFirstByToken($token)
    {
        $userToken = RememberTokens::findFirst([
            'conditions' => 'token = :token:',
            'bind'       => [
                'token' => $token,
            ],
        ]);

        $user_id = ($userToken) ? $userToken->usersId : false;
        return $user_id;
    }

    /**
     * Delete the current user token in session
     */
    protected function deleteToken($userId)
    {
        $user = RememberTokens::find([
            'conditions' => 'usersId = :userId:',
            'bind'       => [
                'userId' => $userId
            ]
        ]);

        if ($user) {
            $user->delete();
        }
    }

    protected function isEmail($login)
    {
        $arrayLogin = explode('@', $login);

        if(count($arrayLogin) > 1) {
            return true;
        } else {
            return false;
        }
    }

    protected function getModel()
    {
        return new Users();
    }
}