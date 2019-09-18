<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class AdminValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'start',
            new PresenceOf(
                [
                    'message' => 'The start field is required',
                ]
            )
        );

        $this->add(
            'stop',
            new PresenceOf(
                [
                    'message' => 'The end field is required',
                ]
            )
        );
    }
}