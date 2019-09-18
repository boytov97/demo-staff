<?php

class NotWorkingDays extends \Phalcon\Mvc\Model
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
    public $month;

    /**
     *
     * @var integer
     */
    public $day;

    /**
     *
     * @var string
     */
    public $repeat;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("staff");
        $this->setSource("not_working_days");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'not_working_days';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NotWorkingDays[]|NotWorkingDays|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NotWorkingDays|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     *
     *
     * @param $month
     * @return NotWorkingDays|NotWorkingDays[]|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public function getAllByMonth($month)
    {
        $items = self::find([
            'conditions' => 'month = :month:',
            'bind'       => [
                'month' => $month,
            ]
        ]);

        return $items;
    }
}
