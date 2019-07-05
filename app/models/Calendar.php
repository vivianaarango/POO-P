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

    public function getCalendarList($id_user){

        $sql = "SELECT * 
                FROM calendar c
                WHERE id_user = $id_user AND current_date < c.fecha
                ORDER BY c.fecha ASC";
       
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        return $prepare;
    }

}
