<?php

class IndividuallyWd extends \Phalcon\Mvc\Model
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
    public $userId;

    /**
     *
     * @var string
     */
    public $createdAt;

    /**
     *
     * @var integer
     */
    public $working_day;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("staff");
        $this->setSource("individually_wd");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'individually_wd';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return IndividuallyWd[]|IndividuallyWd|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return IndividuallyWd|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getByWorkingDay($workingDay)
    {
        $individuallyWds = self::find([
            'conditions' => 'working_day = :workingDay:',
            'bind' => [
                'workingDay' => $workingDay
            ]
        ]);

        return $individuallyWds;
    }
}
