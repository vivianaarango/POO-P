<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class QuestionGame extends Model
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
    public $id_question;

    /**
     *
     * @var integer
     */
    public $id_game;

    /**
     *
     * @var boolean
     */
    public $result;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

}
