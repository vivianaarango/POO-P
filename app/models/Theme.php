<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Theme extends Model
{

    /**
     *
     * @var integer
     */
    public $id_theme;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
