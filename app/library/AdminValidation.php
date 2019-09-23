<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Regex;

class AdminValidation extends Validation
{
    public function initialize()
    {
        $this->add(
            'start',
            new Callback(
                [
                    'callback' => function($post) {
                        if(empty($post['start'])) {
                            return new PresenceOf(
                                [
                                    'message' => 'The end field is required',
                                ]
                            );
                        }

                        if(!empty($post['start']) && $post['start'] !== 'forgot') {

                            if(isset($post['stop']) && strtotime($post['start']) > strtotime($post['stop'])) {
                                return false;
                            }

                            return new Regex([
                                'pattern' => "/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/",
                                'message' => "The start time is invalid"
                            ]);
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
                            if(empty($post['stop']) && $post['start'] !== 'forgot') {

                                return new PresenceOf(
                                    [
                                        'message' => 'The end field is required',
                                    ]
                                );
                            }

                            if(!empty($post['stop']) && $post['stop'] !== 'forgot') {

                                return new Regex([
                                    'pattern' => "/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/",
                                    'message' => "The stop time is invalid"
                                ]);
                            }
                        }

                        return true;
                    }
                ]
            )
        );
    }
}