<?php

class Hours extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $usersId;

    /**
     *
     * @var string
     */
    public $total;

    /**
     *
     * @var string
     */
    public $less;

    /**
     *
     * @var string
     */
    public $late;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("staff");
        $this->setSource("hours");

        $this->belongsTo('usersId', 'Users', 'id', [
            'alias' => 'user'
        ]);

        $this->hasMany('id', 'StartEnd', 'hourId', [
            'alias' => 'startEnds',
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
        return 'hours';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hours[]|Hours|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hours|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Возвращает
     *
     * @param $createdAt
     * @param $userId
     * @return array|Hours|Hours[]|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public function getByCreatedAt($createdAt, $userId)
    {
        $hours = self::find([
            'conditions' => 'createdAt LIKE :createdAt: AND usersId = :id:',
            'bind'       => [
                'createdAt' => $createdAt,
                'id'        => $userId
            ]
        ]);

        return $hours ? $hours->toArray() : $hours;
    }

    public function getLateCountByCreatedAt($createdAt)
    {
        $lateCountPerMonth = self::find([
            'conditions' => 'createdAt LIKE :createdAt: AND late = :late:',
            'bind'       => [
                'createdAt' => $createdAt,
                'late'      => 1
            ]
        ]);

        return $lateCountPerMonth ? $lateCountPerMonth->count() : $lateCountPerMonth;
    }

    public function getAuthLateCountByCreatedAt($createdAt, $userId)
    {
        $authUserLateCount = self::find([
            'conditions' => 'createdAt LIKE :createdAt: AND usersId = :id: AND late = :late:',
            'bind'       => [
                'createdAt' => $createdAt,
                'id'        => $userId,
                'late'      => 1
            ]
        ]);

        return $authUserLateCount ? $authUserLateCount->count() : $authUserLateCount;
    }
}
