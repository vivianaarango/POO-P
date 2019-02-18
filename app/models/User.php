<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class User extends Model
{

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var String
     */
    public $phone;
    
    /**
     *
     * @var String
     */
    public $email;
    
    /**
     *
     * @var String
     */
    public $password;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
