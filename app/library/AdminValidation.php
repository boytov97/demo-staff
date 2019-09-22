<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback;

class AdminValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'start',
            new Callback(
                [
                    'callback' => function($post) {
                        if(isset($post['start'])) {
                            if(isset($post['stop']) && strtotime($post['start']) > strtotime($post['stop'])) {
                                return false;
                            } else {

                                return new PresenceOf(
                                    [
                                        'message' => 'The end field is required',
                                    ]
                                );
                            }
                        }

                        return true;
                    },
                    'message' => 'The start time should be less than stop time'
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