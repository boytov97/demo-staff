<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email as ElementEmail;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{
    public function initialize()
    {
        $email = new ElementEmail('email', [
            'autocomplete' => 'off',
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
            'autocomplete' => 'off',
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

        $remember = new Check('remember', [
            'value' => 'yes',
            'class' => 'form-check-input',
            'id'    => 'exampleCheck1'
        ]);

        $remember->setLabel('Remember me');

        $this->add($remember);

        $csrf = new Hidden('csrf');

        $csrf->addValidators([
            new Identical([
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ])
        ]);

        $csrf->clear();

        $this->add($csrf);

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