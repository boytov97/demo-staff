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
            new PresenceOf([
                'message' => 'The login is required'
            ]),
            new Regex([
                'pattern' => "/^[a-z]+([-_]?[a-z0-9]+){0,2}$/",
                'message' => "The creation login is invalid"
            ])
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
            new PresenceOf([
                'message' => 'The email is required'
            ]),
            new Email([
                'message' => 'The email is not valid'
            ])
        ]);

        $this->add($email);

        $password = new Password('password', [
            'placeholder' => 'Password',
            'class' => 'form-control',
            'id' => 'exampleInputPassword1'
        ]);

        $password->addValidators([
            new PresenceOf([
                'message' => 'The password is required'
            ])
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
            new PresenceOf([
                'message' => 'The confirmation password is required'
            ])
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