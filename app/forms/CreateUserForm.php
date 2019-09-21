<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email as ElementEmail;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Confirmation;

class CreateUserForm extends Form
{
    public function initialize()
    {
        $name = new Text('name', [
            'placeholder' => 'Name',
            'class' => 'form-control',
            'id' => 'nameInput'
        ]);

        $name->addValidators([
            new PresenceOf([
                'message' => 'The name is required'
            ]),
            new StringLength([
                'max' => 255,
                'messageMinimum' => 'Name is too long. Maximum 255 characters'
            ])
        ]);

        $this->add($name);

        $login = new Text('login',[
            'placeholder' => 'Login',
            'class' => 'form-control',
            'id' => 'nameLogin'
        ]);

        $login->addValidators([
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['login'])) {
                            if(empty($post['login'])) {
                                return new PresenceOf([
                                    'message' => 'The login is required'
                                ]);
                            } else {
                                return new Regex([
                                    'pattern' => "/^[a-z]+([-_]?[a-z0-9]+){0,2}$/",
                                    'message' => "The creation login is invalid"
                                ]);
                            }
                        }

                        return true;
                    }
                ]
            )
        ]);

        $this->add($login);

        $profile = new Select('profilesId',
            Profiles::find(),
            [
                'using' => [
                    'id',
                    'name',
                ],
                'useEmpty'   => true,
                'emptyText'  => 'Select one...',
                'emptyValue' => '',
                'class' => 'form-control',
            ]
        );

        $profile->addValidators([
            new PresenceOf([
                'message' => 'The profile is required'
            ])
        ]);

        $this->add($profile);

        $email = new ElementEmail('email', [
            'placeholder' => 'Email',
            'class' => 'form-control',
            'id' => 'exampleInputEmail1'
        ]);

        $email->addValidators([
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['email'])) {
                            if(empty($post['email'])) {
                                return new PresenceOf([
                                    'message' => 'The email is required'
                                ]);
                            } else {
                                return new Email([
                                    'message' => 'The email is not valid'
                                ]);
                            }
                        }

                        return true;
                    }
                ]
            )
        ]);

        $this->add($email);

        $password = new Password('password', [
            'placeholder' => 'Password',
            'class' => 'form-control',
            'id' => 'exampleInputPassword1'
        ]);

        $password->addValidators([
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['password'])) {
                            if(empty($post['password'])) {
                                return new PresenceOf([
                                    'message' => 'The password is required'
                                ]);
                            } else {
                                return new Confirmation([
                                    'message' => 'Password doesn\'t match confirmation',
                                    'with' => 'confirmPassword'
                                ]);
                            }
                        }

                        return true;
                    }
                ]
            )
        ]);

        $password->clear();

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'placeholder' => 'Confirm password',
            'class' => 'form-control',
            'id'    => 'confirmPassword'
        ]);

        $confirmPassword->addValidators([
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['confirmPassword'])) {
                            return new PresenceOf([
                                'message' => 'The confirmPassword is required'
                            ]);
                        }

                        return true;
                    }
                ]
            )
        ]);

        $this->add($confirmPassword);

        $button = new Submit('submit', [
            'class' => 'btn btn-primary'
        ]);

        $this->add($button);
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}