<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\File as FileValidator;

class ProfileForm extends Form
{
    public function initialize()
    {
        $file = new File('image', [
            'accept' => 'image/*'
        ]);

        $file->addValidators([
            new FileValidator(
                [
                    'allowEmpty' => true,
                    "maxSize" => "2M",
                    "messageSize" => ":field exceeds the max filesize (:max)",
                    "allowedTypes" => [
                        "image/jpeg",
                        "image/png",
                        "image/jpg",
                    ],
                    "messageType" => "Allowed file types are :types",
                ]
            )
        ]);

        $this->add($file);

        $name = new Text('name', [
            'class' => 'form-control',
            'id' => 'nameInput',
            'placeholder' => 'Name'
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
