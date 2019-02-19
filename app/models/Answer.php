<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Answer extends Model
{

    /**
     *
     * @var integer
     */
    public $id_answer;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var boolean
     */
    public $is_correct;

    /**
     *
     * @var integer
     */
    public $id_question;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }

    

}
