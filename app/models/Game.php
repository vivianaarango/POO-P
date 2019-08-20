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
     * @var integer
     */
    public $difficulty;

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

    public function countAnswerTrue($id_user, $difficulty)
    {
        $sql = " SELECT COUNT(qg.id)
                 FROM question_game qg
                 INNER JOIN game gm ON gm.id_game = qg.id_game
                 WHERE gm.id_user = $id_user and difficulty = $difficulty and qg.result = true";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();

        return $prepare;
    }


    public function countAnswer($id_user, $difficulty)
    {
        $sql = " SELECT COUNT(qg.id)
                 FROM question_game qg
                 INNER JOIN game gm ON gm.id_game = qg.id_game
                 WHERE gm.id_user = $id_user and difficulty = $difficulty";
        $prepare = $this->getDi()->getShared("db")->prepare($sql);
        $prepare->execute();
        
        return $prepare;
    }

}
