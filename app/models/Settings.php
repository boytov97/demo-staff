<?php

class Settings extends \Phalcon\Mvc\Model
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
    public $key;

    /**
     *
     * @var string
     */
    public $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("staff");
        $this->setSource("settings");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'settings';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Settings[]|Settings|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Settings|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getByKey($key)
    {
        $item = self::findFirst([
            'conditions' => 'key = :key:',
            'bind' => [
                'key' => $key
            ]
        ]);

        return $item;
    }

    public function getValueByKey($key)
    {
        $item = self::findFirst([
            'conditions' => 'key = :key:',
            'bind' => [
                'key' => $key
            ]
        ]);

        return $item ? $item->value : null;
    }

    public function getTest($key)
    {
        $item = self::findFirst([
            'conditions' => 'key = :key:',
            'bind' => [
                'key' => $key
            ]
        ]);

        return $item;
    }
}
