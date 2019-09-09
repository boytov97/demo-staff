<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email as ElementEmail;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class ForgotPasswordForm extends Form
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

        $button = new Submit('send', [
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