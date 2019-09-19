<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Callback;

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
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['stop'])) {
                            return new PresenceOf(
                                [
                                    'message' => 'The end field is required',
                                ]
                            );
                        }

                        return true;
                    }
                ]
            )
        );
    }
}