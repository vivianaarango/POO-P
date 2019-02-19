<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class UserDifficulty extends Model
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
    public $id_user;

    /**
     *
     * @var integer
     */
    public $difficulty;

    /**
     *
     * @var boolean
     */
    public $is_approved;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
