<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Game extends Model
{

    /**
     *
     * @var integer
     */
    public $id_game;

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var String
     */
    public $register_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
