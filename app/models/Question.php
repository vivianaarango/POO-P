<?php

use \Phalcon\Mvc\Model;

/**
 * 
 */
class Question extends Model
{

    /**
     *
     * @var integer
     */
    public $id_question;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var integer
     */
    public $difficulty;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
    }


    public function getQuestion($difficulty, $limit = 1)
    {
        $sql = " SELECT *
                 FROM question q
                 WHERE q.difficulty = $difficulty
                 ORDER BY random() LIMIT $limit ";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        return $prepare;
    }
}
