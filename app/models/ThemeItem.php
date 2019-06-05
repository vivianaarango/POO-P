<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class ThemeItem extends Model
{

    /**
     *
     * @var integer
     */
    public $id_item;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $id_theme;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
