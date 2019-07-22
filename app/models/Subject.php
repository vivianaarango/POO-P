<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Subject extends Model
{

    /**
     *
     * @var integer
     */
    public $id_subject;

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
