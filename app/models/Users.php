<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var integer
     */
    public $profilesId;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var
     */
    public $image;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("staff");
        $this->setSource("users");

        $this->belongsTo('profilesId', 'Profiles', 'id', [
            'alias' => 'profile',
            'reusable' => true
        ]);

        $this->hasMany('id', 'ResetPasswords', 'usersId', [
            'alias' => 'resetPasswords',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
            ]
        ]);

        $this->hasMany('id', 'Hours', 'usersId', [
            'alias' => 'hours',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
            ]
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function checkUniqueness($email)
    {
        $user = Users::findFirst([
            'conditions' => 'email = :email:',
            'bind' => [
                'email' => $email
            ]
        ]);

        return $user;
    }

    public function getAll()
    {
        $users = (new self())->modelsManager->createBuilder()
            ->from('Users')->orderBy('profilesId')->getQuery()
            ->execute();

        return $users;
    }
}
