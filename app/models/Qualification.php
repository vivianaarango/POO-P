<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Qualification extends Model
{

    /**
     *
     * @var integer
     */
    public $id_qualification;

    /**
     *
     * @var integer
     */
    public $id_user;

    /**
     *
     * @var integer
     */
    public $cut;

    /**
     *
     * @var integer
     */
    public $qualification;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
