<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Calendar extends Model
{

    /**
     *
     * @var integer
     */
    public $id_calendar;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    

}
